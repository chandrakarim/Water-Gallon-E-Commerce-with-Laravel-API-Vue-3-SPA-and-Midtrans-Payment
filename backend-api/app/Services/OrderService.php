<?php

namespace App\Services;

use App\Models\Address;
use App\Models\Order;
use App\Models\Payment;
use App\Models\User;
use App\Services\MidtransService;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class OrderService
{
    public function checkout(User $user, int $addressId, string $paymentMethod): Order
    {
        return DB::transaction(function () use ($user, $addressId, $paymentMethod) {

            // =============================
            // 1. VALIDASI ADDRESS MILIK USER
            // =============================
            $address = Address::where('id', $addressId)
                ->where('user_id', $user->id)
                ->first();

            if (!$address) {
                throw ValidationException::withMessages([
                    'address_id' => ['Alamat tidak valid']
                ]);
            }

            // =============================
            // 2. AMBIL CART
            // =============================
            $cartItems = $user->cartItems()->with('product')->get();

            if ($cartItems->isEmpty()) {
                throw ValidationException::withMessages([
                    'cart' => ['Keranjang kosong']
                ]);
            }

            // =============================
            // 3. TENTUKAN STATUS BERDASARKAN PAYMENT
            // =============================
            if ($paymentMethod === 'MIDTRANS') {
                $orderStatus = 'WAITING_PAYMENT';
                $paymentStatus = 'PENDING';
            } elseif ($paymentMethod === 'COD') {
                $orderStatus = 'WAITING_ASSIGN';
                $paymentStatus = 'UNPAID';
            } else { // TRANSFER manual
                $orderStatus = 'WAITING_PAYMENT';
                $paymentStatus = 'PENDING';
            }

            // =============================
            // 4. CREATE ORDER
            // =============================
            $order = Order::create([
                'invoice_number' => $this->generateInvoiceNumber(),
                'user_id' => $user->id,
                'address_id' => $address->id,
                'payment_method' => $paymentMethod,
                'status' => $orderStatus,
                'payment_status' => $paymentStatus,
                'ordered_at' => now(),
            ]);

            // =============================
            // 5. CREATE ORDER ITEMS
            // =============================
            $total = 0;

            foreach ($cartItems as $item) {

                $product = $item->product;

                if ($product->stock < $item->qty) {
                    throw ValidationException::withMessages([
                        'stock' => ["Stock {$product->name} tidak cukup"]
                    ]);
                }

                $product->decrement('stock', $item->qty);

                $subtotal = $item->product->price * $item->qty;

                $order->items()->create([
                    'product_id' => $item->product->id,
                    'product_name_snapshot' => $item->product->name,
                    'price_snapshot' => $item->product->price,
                    'quantity' => $item->qty,
                    'price' => $item->product->price,
                    'subtotal' => $subtotal,
                ]);

                $total += $subtotal;

                Payment::create([
                    'order_id' => $order->id,
                    'user_id' => $user->id,
                    'payment_method' => $paymentMethod,
                    'status' => 'PENDING',
                    'gateway' => $paymentMethod === 'MIDTRANS' ? 'MIDTRANS' : 'COD'
                ]);
            }

            // =============================
            // 6. UPDATE TOTAL
            // =============================
            $order->update([
                'total_price' => $total
            ]);

            // =============================
            // 7. MIDTRANS SNAP
            // =============================
            if ($paymentMethod === 'MIDTRANS') {

                $midtrans = app(MidtransService::class)
                    ->getSnapToken($order);

                $order->update([
                    'midtrans_order_id' => $midtrans['midtrans_order_id'],
                    'snap_token' => $midtrans['snap_token']
                ]);
            }

            // =============================
            // 8. CLEAR CART
            // =============================
            $user->cartItems()->delete();

            return $order->load(['items', 'address']);
        });
    }

    private function generateInvoiceNumber()
    {
        $date = now()->format('Ymd');

        $lastOrder = Order::whereDate('created_at', now()->toDateString())
            ->latest()
            ->first();

        $sequence = 1;

        if ($lastOrder) {
            $lastNumber = (int) substr($lastOrder->invoice_number, -4);
            $sequence = $lastNumber + 1;
        }

        return 'INV-' . $date . '-' . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }

    public function assignCourier(Order $order, int $courierId): Order
    {
        if (! $order->canBeAssigned()) {
            throw ValidationException::withMessages([
                'status' => ['Order belum siap ditugaskan']
            ]);
        }

        $order->update([
            'courier_id' => $courierId,
            'status' => Order::STATUS_ASSIGNED
        ]);

        return $order;
    }

    public function pickup(Order $order): Order
    {
        if (! $order->canBePickedUp()) {
            throw ValidationException::withMessages([
                'status' => ['Order belum bisa di-pickup']
            ]);
        }

        DB::transaction(function () use ($order) {

            // Update status order
            $order->update([
                'status' => Order::STATUS_PICKED_UP
            ]);

            // Update delivery record
            $order->delivery()->updateOrCreate(
                ['order_id' => $order->id],
                [
                    'courier_id' => $order->courier_id,
                    'status' => 'PICKED_UP',
                    'picked_at' => now(), // 🔥 WAJIB ADA
                ]
            );
        });

        return $order->fresh(['delivery']);
    }

    public function markAsDelivered(Order $order, ?string $photo = null, ?string $notes = null): Order
    {
        return DB::transaction(function () use ($order, $photo, $notes) {

            $order->update([
                'status' => Order::STATUS_DELIVERED
            ]);

            // 🔥 TAMBAHAN PENTING UNTUK COD
            if ($order->isCOD()) {
                $order->update([
                    'payment_status' => 'PAID',
                    'paid_at' => now()
                ]);
            }

            $order->delivery()->updateOrCreate(
                ['order_id' => $order->id],
                [
                    'courier_id' => $order->courier_id,
                    'status' => 'DELIVERED',
                    'delivery_photo' => $photo,
                    'notes' => $notes,
                    'delivered_at' => now(),
                ]
            );

            return $order->fresh(['delivery']);
        });
    }
}

<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;

class CartService
{
    public function add($user, $productId, $qty)
    {
        // ambil product dulu
        $product = Product::findOrFail($productId);

        // ambil atau buat cart
        $cart = Cart::firstOrCreate([
            'user_id' => $user->id
        ]);

        // cek item
        $item = CartItem::where('cart_id', $cart->id)
            ->where('product_id', $productId)
            ->first();

        if ($item) {
            // kalau sudah ada → tambah qty saja
            $item->increment('qty', $qty);
        } else {
            // kalau belum → buat baru + simpan harga saat ini
            CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $productId,
                'qty' => $qty,
                'price_snapshot' => $product->price
            ]);
        }
    }


    public function getCart($user)
    {
        return Cart::with('items.product')
            ->firstOrCreate([
                'user_id' => $user->id
            ]);
    }

    public function updateQty($user, $itemId, $qty)
    {
        $item = CartItem::where('id', $itemId)
            ->whereHas('cart', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->firstOrFail();

        $item->update([
            'qty' => $qty
        ]);

        return $item;
    }

    public function removeItem($user, $itemId)
    {
        $item = CartItem::where('id', $itemId)
            ->whereHas('cart', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->firstOrFail();

        $item->delete();
    }

    public function clearCart($user)
    {
        $cart = Cart::where('user_id', $user->id)->first();

        if ($cart) {
            $cart->items()->delete();
        }
    }
}

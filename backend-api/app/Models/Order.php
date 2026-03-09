<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'invoice_number',
        'midtrans_order_id',
        'user_id',
        'address_id',
        'courier_id',
        'total_price',
        'status',
        'payment_status',
        'payment_method',
        'snap_token',
        'payment_url',
        'midtrans_transaction_id',
        'midtrans_payment_type',
        'midtrans_payload',
        'paid_at',
        'ordered_at'
    ];

    protected $casts = [
        'ordered_at' => 'datetime',
        'paid_at' => 'datetime',
        'midtrans_payload' => 'array',
    ];

    // ================= STATUS CONSTANT =================

    public const STATUS_WAITING_PAYMENT = 'WAITING_PAYMENT';
    public const STATUS_WAITING_ASSIGN  = 'WAITING_ASSIGN';
    public const STATUS_ASSIGNED        = 'ASSIGNED';
    public const STATUS_PICKED_UP       = 'PICKED_UP';
    public const STATUS_DELIVERED       = 'DELIVERED';
    public const STATUS_CANCELLED       = 'CANCELLED';

    public function getStatusLabelAttribute()
    {
        return match ($this->status) {
            self::STATUS_WAITING_PAYMENT => 'Menunggu Pembayaran',
            self::STATUS_WAITING_ASSIGN  => 'Menunggu Kurir',
            self::STATUS_ASSIGNED        => 'Kurir Ditugaskan',
            self::STATUS_PICKED_UP       => 'Sedang Diantar',
            self::STATUS_DELIVERED       => 'Selesai',
            self::STATUS_CANCELLED       => 'Dibatalkan',
            default => $this->status
        };
    }

    // ================= RELATIONS =================

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function address()
    {
        return $this->belongsTo(Address::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function courier()
    {
        return $this->belongsTo(User::class, 'courier_id');
    }

    public function delivery()
    {
        return $this->hasOne(Delivery::class);
    }

    // ================= PAYMENT HELPERS =================

    public function isCOD(): bool
    {
        return $this->payment_method === 'COD';
    }

    public function isMidtrans(): bool
    {
        return $this->payment_method === 'MIDTRANS';
    }

    public function isPaid(): bool
    {
        return $this->payment_status === 'PAID';
    }

    public function markAsPaid(): void
    {
        $this->update([
            'payment_status' => 'PAID',
            'paid_at' => now(),
        ]);
    }

    // ================= FLOW HELPERS =================

    public function canBeAssigned(): bool
    {
        if ($this->isMidtrans()) {
            return $this->isPaid()
                && $this->status === self::STATUS_WAITING_ASSIGN;
        }

        if ($this->isCOD()) {
            return $this->status === self::STATUS_WAITING_ASSIGN;
        }

        return false;
    }

    public function canBePickedUp(): bool
    {
        return $this->status === self::STATUS_ASSIGNED;
    }

    public function canBeDelivered(): bool
    {
        return $this->status === self::STATUS_PICKED_UP;
    }

    public function histories()
    {
        return $this->hasMany(OrderHistory::class);
    }

    public function tracks()
    {
        return $this->hasMany(\App\Models\OrderHistory::class);
    }
}

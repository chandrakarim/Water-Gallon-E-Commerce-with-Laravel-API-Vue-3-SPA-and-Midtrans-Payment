<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $fillable = [
        'user_id',
        'recipient_name',
        'phone',
        'province',
        'city',
        'district',
        'detail',
        'label',
        'note'
    ];

    protected $appends = ['full_address'];

    public function getFullAddressAttribute()
    {
        return "{$this->detail}, {$this->district}, {$this->city}, {$this->province}";
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}

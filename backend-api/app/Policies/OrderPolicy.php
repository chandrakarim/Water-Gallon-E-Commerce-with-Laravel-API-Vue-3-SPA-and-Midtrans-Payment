<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\User;

class OrderPolicy
{
    // ADMIN assign kurir
    public function assign(User $user, Order $order): bool
    {
        if ($user->role !== 'admin') {
            return false;
        }

        return $order->canBeAssigned();
    }

    public function pickup(User $user, Order $order): bool
    {
        return $user->role === 'courier'
            && $order->courier_id === $user->id
            && $order->canBePickedUp();
    }

    public function deliver(User $user, Order $order): bool
    {
        return $user->role === 'courier'
            && $order->courier_id === $user->id
            && $order->canBeDelivered();
    }

    public function view(User $user, Order $order)
    {
        return $user->id === $order->user_id;
    }
}

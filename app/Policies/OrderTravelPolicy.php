<?php

namespace App\Policies;

use App\Models\OrderTravel;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrderTravelPolicy
{
    use HandlesAuthorization;

    public function permission(User $user, OrderTravel $orderTravel): bool
    {
        return $user->id == $orderTravel->user_id;
    }
}

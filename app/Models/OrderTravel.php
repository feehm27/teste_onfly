<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class OrderTravel extends Model
{
    use HasFactory;

    protected $table = 'order_travels';

    protected $hidden = ['id', 'created_at', 'updated_at'];

    protected $fillable = [
        'order_travel_status_id',
        'name_applicant',
        'destination',
        'departure_date',
        'return_date',
        'user_id',
    ];

    public function status(): HasOne
    {
        return $this->hasOne(OrderTravelStatus::class, 'travel_status_id');
    }
}

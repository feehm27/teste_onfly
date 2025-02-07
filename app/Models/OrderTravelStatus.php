<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderTravelStatus extends Model
{
    protected $table = 'order_travel_status';
    protected $hidden = ['id', 'created_at', 'updated_at'];

    protected $fillable = [
        'id',
        'status'
    ];
}

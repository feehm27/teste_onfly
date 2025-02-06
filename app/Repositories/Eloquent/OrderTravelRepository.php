<?php

namespace app\Repositories\Eloquent;

use App\Models\OrderTravel;
use App\Repositories\Contracts\OrderTravelRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use App\Repositories\Eloquent\AbstractEloquentRepository;

class OrderTravelRepository extends AbstractEloquentRepository implements OrderTravelRepositoryInterface
{
    protected function resolveModel(): Model
    {
        return app(OrderTravel::class);
    }
}

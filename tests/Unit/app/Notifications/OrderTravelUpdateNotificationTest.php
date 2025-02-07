<?php

namespace Tests\Unit\app;

use App\Models\OrderTravel;
use App\Models\User;
use App\Notifications\OrderTravelUpdatedNotification;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class OrderTravelUpdateNotificationTest extends TestCase
{
    public function testDatabaseNotificationIsStoredWhenOrderTravelIsUpdated()
    {
        Notification::fake();

        $orderTravel = OrderTravel::factory()->create([]);
        $user = User::factory()->create();

        $user->notify(new OrderTravelUpdatedNotification($orderTravel));

        Notification::assertSentTo(
            [$user],
            OrderTravelUpdatedNotification::class,
            function ($notification) use ($user, $orderTravel) {
                return $notification->toDatabase($user)['order_id'] === $orderTravel->id &&
                    $notification->toDatabase($user)['message'] === 'Seu pedido de viagem foi atualizado para o status ' . $orderTravel->status->status;
            }
        );
    }
}

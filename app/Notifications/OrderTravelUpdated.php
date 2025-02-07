<?php

namespace App\Notifications;

use App\Models\OrderTravel;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class OrderTravelUpdated extends Notification
{
    protected OrderTravel $orderTravel;
    protected $status;

    public function __construct(OrderTravel $orderTravel)
    {
        $this->orderTravel = $orderTravel;
        $this->status = $orderTravel->status->status;
    }

    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'order_id' => $this->orderTravel->id,
            'message' => 'Seu pedido de viagem foi atualizado para o status '. $this->status,
        ];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'order_id' => $this->orderTravel->id,
            'message' => 'Seu pedido de viagem foi atualizado para o status '. $this->status,
        ]);
    }
}

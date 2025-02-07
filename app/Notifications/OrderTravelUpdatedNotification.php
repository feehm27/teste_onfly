<?php

namespace App\Notifications;

use App\Mail\OrderTravelUpdatedMail;
use App\Models\OrderTravel;
use Illuminate\Notifications\Notification;

class OrderTravelUpdatedNotification extends Notification
{
    protected OrderTravel $orderTravel;

    public function __construct(OrderTravel $orderTravel)
    {
        $this->orderTravel = $orderTravel;
    }

    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    public function toDatabase($notifiable)
    {
        $status = $this->orderTravel->status->status;

        return [
            'order_id' => $this->orderTravel->id,
            'message' => 'Seu pedido de viagem foi atualizado para o status ' . $status,
        ];
    }

    public function toMail($notifiable)
    {
        return (new OrderTravelUpdatedMail($this->orderTravel));
    }
}

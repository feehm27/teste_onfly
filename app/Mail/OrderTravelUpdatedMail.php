<?php

namespace App\Mail;

use App\Models\OrderTravel;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderTravelUpdatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $orderTravel;

    /**
     * Create a new message instance.
     *
     * @param OrderTravel $orderTravel
     * @return void
     */
    public function __construct(OrderTravel $orderTravel)
    {
        $this->orderTravel = $orderTravel;
    }

    /**
     * Defina o envelope do email, incluindo o assunto e outras propriedades.
     *
     * @return Envelope
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'O status do pedido ' . $this->orderTravel->id . ' foi atualizado',
        );
    }

    /**
     * Construir o corpo da mensagem do email.
     *
     * @return $this
     */
    public function build()
    {
        return $this->to($this->orderTravel->user->email)
        ->view('mail.order_travel_updated')
        ->with([
            'orderTravel' => $this->orderTravel,
            'status' => $this->orderTravel->status->status,
        ]);
    }
}

<?php
namespace App\Exceptions;

use Exception;

class OrderTravelCanceledException extends Exception
{
    public function __construct($code = 0, Exception $previous = null)
    {
        $message = 'O pedido excedeu o prazo de 24 horas para aprovação e, por isso, não pode ser cancelado.';
        parent::__construct($message, $code, $previous);
    }
}

<?php

namespace App\Exceptions;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class CancellationNotAllowedException extends Exception
{
    public function __construct(int $code = Response::HTTP_BAD_REQUEST, Exception $previous = null)
    {
        $message = 'O pedido excedeu o prazo de 24 horas para aprovação e, por isso, não pode ser cancelado.';
        parent::__construct($message, $code, $previous);
    }
}

<?php

namespace App\Exceptions;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class OrderTravelNotFoundException extends Exception
{
    public function __construct(int $code = Response::HTTP_NOT_FOUND, Exception $previous = null)
    {
        $message = 'Pedido de viagem não encontrado. Verifique se o ID está correto.';
        parent::__construct($message, $code, $previous);
    }

    public function render()
    {
        return response()->json(['error' => $this->getMessage()], $this->getCode());
    }
}

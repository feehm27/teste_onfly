<?php

namespace App\Exceptions;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class UnauthorizedException extends Exception
{
    public function __construct($isUpdated = false, int $code = Response::HTTP_FORBIDDEN, Exception $previous = null)
    {
        $message = 'Você não tem permissão para visualizar essa solicitação de viagem.';

        if ($isUpdated) {
            $message = 'Você não tem permissão para editar essa solicitação de viagem.';
        }

        parent::__construct($message, $code, $previous);
    }

    public function render()
    {
        return response()->json(['error' => $this->getMessage()], $this->getCode());
    }
}

<?php

namespace App\Rules;

use App\Models\OrderTravel;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;

class OrderTravelDoesNotExist implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $exists = OrderTravel::find($value);

        if (!$exists) {
            throw new HttpResponseException(
                response()->json([
                    'error' => 'Pedido de viagem não encontrado. Verifique se o ID está correto.',
                ], Response::HTTP_NOT_FOUND)
            );
        }
    }
}

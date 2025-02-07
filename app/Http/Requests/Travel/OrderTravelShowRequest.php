<?php

namespace App\Http\Requests\Travel;

use App\Exceptions\UnauthorizedException;
use App\Exceptions\OrderTravelNotFoundException;
use App\Models\OrderTravel;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;

class OrderTravelShowRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     * @throws OrderTravelNotFoundException
     * @throws UnauthorizedException
     */
    public function authorize(): bool
    {
        $orderTravel = OrderTravel::find($this->route('travel'));

        if (!$orderTravel) {
            throw new OrderTravelNotFoundException();
        }

        if (!$this->user()->can('permission', $orderTravel)) {
            throw new UnauthorizedException();
        }

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'id' => ['required', 'integer'],
        ];
    }

    /**
     * Return validation errors as json response
     *
     * @param Validator $validator
     */
    protected function failedValidation(Validator $validator)
    {
        $response = ['errors' => $validator->errors()];
        throw new HttpResponseException(response()->json($response, Response::HTTP_UNPROCESSABLE_ENTITY));
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'id' => $this->route('travel'),
        ]);
    }

    private function teste(){

    }
}

<?php

namespace App\Http\Requests\Travel;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class OrderTravelStoreRequest
 *
 * @OA\Schema(
 *     title="OrderTravelStoreRequest",
 *     required={"name_applicant", "destination", "departure_date", "return_date"},
 *     @OA\Xml(
 *         name="OrderTravelStoreRequest"
 *     )
 * )
 */
class OrderTravelStoreRequest extends FormRequest
{
    /**
     * @OA\Property(
     *     format="string",
     *     description="Nome do solicitante",
     *     title="name_applicant",
     *     example="Fernando Amorim da Silva"
     * )
     *
     * @var string
     */
    protected $name_applicant;

    /**
     * @OA\Property(
     *     format="string",
     *     description="Destino da viagem",
     *     title="destination",
     *     example="Rio de Janeiro"
     * )
     *
     * @var string
     */
    protected $destination;

    /**
     * @OA\Property(
     *     format="string",
     *     description="Data de ida",
     *     title="departure_date",
     *     example="2025-02-05 08:30:00"
     * )
     *
     * @var date
     */
    protected $departure_date;

    /**
     * @OA\Property(
     *     format="string",
     *     description="Data de retorno",
     *     title="return_date",
     *     example="2025-02-10 18:30:00"
     * )
     *
     * @var date
     */
    protected $return_date;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
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
            'name_applicant' => ['required','string'],
            'destination' => ['required','string'],
            'departure_date' => ['required','date_format:Y-m-d H:i:s','after_or_equal:today'],
            'return_date' => ['required', 'date_format:Y-m-d H:i:s', 'after:departure_date'],
            'user_id' => ['required','integer','exists:users,id'],
        ];
    }

    public function messages()
    {
        return [
            'departure_date.after_or_equal' => 'O campo :attribute deve conter uma data posterior a data/horário atual.',
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'user_id' => Auth::id()
        ]);
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
}

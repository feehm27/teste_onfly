<?php

namespace App\Http\Requests\Travel;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;

class OrderTravelIndexRequest extends FormRequest
{
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
            'order_travel_status_id' => ['nullable', 'exists:order_travel_status,id'],
            'paginate' => ['nullable', 'boolean'],
            'departure_date' => ['nullable', 'date_format:Y-m-d'],
            'return_date' => ['nullable', 'date_format:Y-m-d'],
            'destination' => ['nullable', 'string'],
            'limit' => ['nullable', 'integer'],
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
        dd($this->input('paginate'));
        $paginate = false;

        if ($this->paginate && $this->paginate == 'true') {
            $paginate = true;
        }

        $this->merge([
            'paginate' => $paginate,
            'limit' => $this->input('limit') ?? 15,
        ]);
    }
}

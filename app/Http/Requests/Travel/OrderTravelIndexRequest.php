<?php

namespace App\Http\Requests\Travel;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;
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
            'return_date' => ['nullable', 'date_format:Y-m-d','after_or_equal:departure_date'],
            'destination' => ['nullable', 'string'],
            'limit' => ['nullable', 'integer'],
            'user_id' => ['required', 'exists:users,id'],
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
        $paginate = true;

        if ($this->paginate && $this->paginate == 'false') {
            $paginate = false;
        }

        $this->merge([
            'paginate' => $paginate,
            'limit' => $this->input('limit') ?? 15,
            'user_id' => Auth::id()
        ]);
    }
}

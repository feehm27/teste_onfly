<?php

namespace Tests\Unit\Http\Requests\Travel;

use App\Http\Requests\Travel\OrderTravelIndexRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Validator;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class OrderTravelIndexRequestTest extends TestCase
{
    use WithFaker;

    public function setUp(): void
    {
        parent::setUp();
        $this->request = new OrderTravelIndexRequest();
    }

    public function testShouldContainAllExpectedRules()
    {
        $expect = [
            'order_travel_status_id' => ['nullable', 'exists:order_travel_status,id'],
            'paginate' => ['nullable', 'boolean'],
            'departure_date' => ['nullable', 'date_format:Y-m-d'],
            'return_date' => ['nullable', 'date_format:Y-m-d','after_or_equal:departure_date'],
            'destination' => ['nullable', 'string'],
            'limit' => ['nullable', 'integer'],
            'user_id' => ['required', 'exists:users,id'],
        ];

        $this->assertEquals($expect, $this->request->rules());
    }

    public function testShouldAcceptValidData()
    {
        $validator = Validator::make([
            'order_travel_status_id' => 2,
            'paginate' => true,
            'departure_date' => Carbon::now()->format('Y-m-d'),
            'return_date' => Carbon::now()->format('Y-m-d'),
            'destination' => 'Belo Horizonte',
            'limit' => 3,
            'user_id' => User::factory()->create()->id,
        ], $this->request->rules());

        $this->assertTrue(!$validator->fails());
    }

    public function testShouldBeAuthorized()
    {
        $this->assertEquals(true, $this->request->authorize());
    }

    public function testShouldReturnDateIsBeforeDepartureDate()
    {
        $request = [
            'departure_date' => Carbon::now()->subDay()->format('Y-m-d'),
            'return_date' => Carbon::now()->subDays(2)->format('Y-m-d')
        ];

        $validator = Validator::make($request, $this->request->rules());
        $errors = $validator->errors();
        $expected = 'O campo return date deve conter uma data superior ou igual a departure date.';

        $this->assertEquals($expected, $errors->get('return_date')[0]);
    }


    #[DataProvider('providerInvalidData')]
    public function testInvalidData(string $fieldName, $value, string $expected)
    {
        $request = [$fieldName => $value];
        $validator = Validator::make($request, $this->request->rules());

        $errors = $validator->errors();
        $this->assertEquals($expected, $errors->get($fieldName)[0]);
    }

    public static function providerInvalidData(): array
    {
        return [
            'orderTravelStatusIdIsInvalid' => ['order_travel_status_id', 99, 'O valor selecionado para o campo order travel status id é inválido.'],
            'destinationIsInteger' => ['destination', 9, 'O campo destination deve ser uma string.'],
            'departureDateIsInvalid' => [
                'departure_date',
                '2025/02/03',
                'A data informada para o campo departure date não respeita o formato Y-m-d.'
            ],
            'returnDateIsInvalid' => [
                'return_date',
                '2025/02/03',
                'A data informada para o campo return date não respeita o formato Y-m-d.'
            ],
        ];
    }
}

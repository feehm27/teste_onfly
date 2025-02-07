<?php

namespace Tests\Unit\Http\Requests\Travel;

use App\Http\Requests\Travel\OrderTravelUpdateRequest;
use App\Models\OrderTravel;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Validator;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class OrderTravelUpdateRequestTest extends TestCase
{
    use WithFaker;

    public static function providerInvalidData(): array
    {
        return [
            'orderTravelStatusIdIsNull' => ['order_travel_status_id', null, 'O campo order travel status id é obrigatório.'],
            'orderTravelStatusIdIsInvalid' => ['order_travel_status_id', 'texto', 'O campo order travel status id deve conter um número inteiro.'],
            'orderTravelStatusIdIsString' => ['order_travel_status_id', 1, 'O campo order travel status id não contém um valor válido.'],
        ];
    }

    public function testIdIsNull()
    {
        $data = ['id' => null];

        $validator = Validator::make($data, [
            'id' => ['required', 'integer'],
        ]);

        $errors = $validator->errors();
        $expected = 'O campo id é obrigatório.';
        $this->assertEquals($expected, $errors->get('id')[0]);
    }

    public function testIdIsString()
    {
        $data = ['id' => 'texto'];

        $validator = Validator::make($data, [
            'id' => ['required', 'integer'],
        ]);

        $errors = $validator->errors();
        $expected = 'O campo id deve conter um número inteiro.';
        $this->assertEquals($expected, $errors->get('id')[0]);
    }

    public function setUp(): void
    {
        parent::setUp();
        $this->request = new OrderTravelUpdateRequest();
    }

    public function testShouldContainAllExpectedRules()
    {
        $expect = [
            'id' => ['required', 'integer'],
            'order_travel_status_id' => ['required', 'integer', 'exists:order_travel_status,id', 'in:2,3'],
        ];

        $this->assertEquals($expect, $this->request->rules());
    }

    public function testShouldAcceptValidData()
    {
        $validator = Validator::make([
            'id' => OrderTravel::factory()->create()->id,
            'order_travel_status_id' => 2,
        ], $this->request->rules());

        $this->assertTrue(!$validator->fails());
    }

    #[DataProvider('providerInvalidData')]
    public function testInvalidData(string $fieldName, $value, string $expected)
    {
        $request = [$fieldName => $value];
        $validator = Validator::make($request, $this->request->rules());

        $errors = $validator->errors();
        $this->assertEquals($expected, $errors->get($fieldName)[0]);
    }
}

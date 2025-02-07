<?php

namespace Tests\Unit\Http\Requests\Travel;

use App\Http\Requests\Travel\OrderTravelShowRequest;
use App\Models\OrderTravel;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class OrderTravelShowRequestTest extends TestCase
{
    use WithFaker;

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

    public function setUp(): void
    {
        parent::setUp();
        $this->request = new OrderTravelShowRequest();
    }

    public function testShouldContainAllExpectedRules()
    {
        $expect = [
            'id' => ['required', 'integer'],
        ];

        $this->assertEquals($expect, $this->request->rules());
    }

    public function testShouldAcceptValidData()
    {
        $validator = Validator::make([
            'id' => OrderTravel::factory()->create()->id,
        ], $this->request->rules());

        $this->assertTrue(!$validator->fails());
    }
}

<?php

namespace Tests\Unit\Http\Requests\Travel;

use App\Http\Requests\Travel\OrderTravelShowRequest;
use App\Models\OrderTravel;
use App\Rules\OrderTravelDoesNotExist;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class OrderTravelShowRequestTest extends TestCase
{
    use WithFaker;

    public function testIdNotExist()
    {
        $data = ['id' => 99999,];
        $this->expectException(HttpResponseException::class);

        $validator = Validator::make($data, [
            'id' => ['required', 'integer', new OrderTravelDoesNotExist()],
        ]);

        $validator->validate();
        $this->expectExceptionMessage('Pedido de viagem não encontrado. Verifique se o ID está correto.');
    }

    public function testIdIsString()
    {
        $data = ['id' => 'texto'];
        $this->expectException(HttpResponseException::class);

        $validator = Validator::make($data, [
            'id' => ['required', 'integer', new OrderTravelDoesNotExist()],
        ]);

        $validator->validate();
        $this->expectExceptionMessage('Pedido de viagem não encontrado. Verifique se o ID está correto.');
    }

    public function setUp(): void
    {
        parent::setUp();
        $this->request = new OrderTravelShowRequest();
    }

    public function testShouldContainAllExpectedRules()
    {
        $expect = [
            'id' => ['required', 'integer', new OrderTravelDoesNotExist()],
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

    public function testShouldBeAuthorized()
    {
        $this->assertEquals(true, $this->request->authorize());
    }
}

<?php

namespace Tests\Feature\app\Http\Controllers;

use App\Models\OrderTravel;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\{DatabaseTransactions, WithoutMiddleware};
use Tests\TestCase;

class OrderTravelControllerTest extends TestCase
{
    use DatabaseTransactions;
    use WithoutMiddleware;

    public function testShouldCreatedTravelOrder()
    {
        $user = User::factory()->create(['id' => 1]);

        $data = [
            'name_applicant' => 'Teste de Feature',
            'destination' => 'São Paulo',
            'departure_date' => Carbon::now()->addDay()->format('Y-m-d H:i:s'),
            'return_date' => Carbon::now()->addDay()->addMinutes(40)->format('Y-m-d H:i:s'),
            'user_id' => $user->id,
        ];

        $response = $this->post('/api/v1/order/travels', $data);
        $response->assertCreated();

        $response->assertJsonFragment([
            'message' => 'Solicitação de viagem criada com sucesso.',
        ]);

        $response->assertJsonStructure($this->getJsonStructure());
    }

    public function testShouldUpdateTravelOrderStatus()
    {
        $orderTravel = OrderTravel::factory()->create(['order_travel_status_id' => 1]);

        $data = [
            'order_travel_status_id' => 2,
        ];

        $response = $this->put('/api/v1/order/travels/' . $orderTravel->id, $data);
        $response->assertSuccessful();

        $response->assertJsonFragment([
            'message' => "Status do pedido alterado com sucesso.",
        ]);

        $response->assertJsonStructure($this->getJsonStructure());
    }

    public function testShouldIndexOrdersTravelWithPagination()
    {
        $response = $this->get('/api/v1/order/travels/');
        $response->assertSuccessful();

        $response->assertJsonPath('current_page', 1);
        $response->assertJsonPath('per_page', 15);
        $response->assertJsonPath('from', 1);
        $response->assertJsonPath('to', 15);

        $response->assertJsonStructure(['data']);
    }

    public function testShouldIndexOrdersTravelWithoutPagination()
    {
        $data = ['paginate' => false];
        $response = $this->get('/api/v1/order/travels/', $data);

        $response->assertSuccessful();
        $response->assertJsonStructure(['data']);
    }

    public function testShouldShowOrderTravel()
    {
        $orderTravel = OrderTravel::factory()->create();

        $response = $this->get('/api/v1/order/travels/' . $orderTravel->id);
        $response->assertSuccessful();

        $response->assertJsonFragment([
            'message' => "Pedido de viagem encontrado com sucesso.",
        ]);

        $response->assertJsonStructure($this->getJsonStructure());
    }

    private function getJsonStructure() :array {
        return [
            'message',
            'data' => [
                'id',
                'name_applicant',
                'destination',
                'departure_date',
                'return_date',
                'user_id',
            ]
        ];
    }
}

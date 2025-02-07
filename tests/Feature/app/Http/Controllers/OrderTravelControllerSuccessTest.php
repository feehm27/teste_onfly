<?php

namespace Tests\Feature\app\Http\Controllers;

use App\Models\OrderTravel;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\{DatabaseTransactions, WithoutMiddleware};
use Tests\TestCase;

class OrderTravelControllerSuccessTest extends TestCase
{
    use DatabaseTransactions;
    use WithoutMiddleware;

    public function setUp(): void
    {
        parent::setUp();
        $this->userAuthenticated = User::factory()->create();
    }

    public function testShouldCreatedTravelOrder()
    {
        $response = $this->actingAs($this->userAuthenticated , 'sanctum')->post('/api/v1/order/travels', [
            'name_applicant' => 'Teste de Feature',
            'destination' => 'São Paulo',
            'departure_date' => Carbon::now()->addDay()->format('Y-m-d H:i:s'),
            'return_date' => Carbon::now()->addDay()->addMinutes(40)->format('Y-m-d H:i:s'),
        ]);

        $response->assertCreated();

        $response->assertJsonFragment([
            'message' => 'Solicitação de viagem criada com sucesso.',
        ]);

        $response->assertJsonStructure($this->getJsonStructure());
    }

    public function testShouldUpdateTravelOrderStatus()
    {
        $userOrderTravel = User::factory()->create();

        $mock = \Mockery::mock('overload:Pusher\Pusher');
        $mock->shouldReceive('trigger')->once()->andReturn(true);

        $orderTravel = OrderTravel::factory()->create(['order_travel_status_id' => 1,'user_id' => $userOrderTravel->id]);

        $data = ['order_travel_status_id' => 2];

        $response = $this->actingAs($this->userAuthenticated, 'sanctum')->put('/api/v1/order/travels/' . $orderTravel->id, $data);
        $response->assertSuccessful();

        $response->assertJsonFragment([
            'message' => "Status do pedido alterado com sucesso.",
        ]);

        $response->assertJsonStructure($this->getJsonStructure());
    }

   public function testShouldIndexOrdersTravelWithPaginationForUserId()
    {
        $user = User::factory()->create();

        OrderTravel::factory()->create(['user_id' => $this->userAuthenticated->id]);
        OrderTravel::factory()->create(['user_id' => $this->userAuthenticated->id]);
        OrderTravel::factory()->create(['user_id' => $user]);

        $response = $this->actingAs($this->userAuthenticated , 'sanctum')->get('/api/v1/order/travels/');

        $response->assertSuccessful();
        $response->assertJsonPath('total', 2);
        $response->assertJsonPath('current_page', 1);
        $response->assertJsonPath('per_page', 15);
        $response->assertJsonPath('from', 1);
        $response->assertJsonPath('to', 2);
        $response->assertJsonStructure(['data']);
    }

    public function testIndexOrdersTravelWithoutPaginationForUserId()
    {
        $user = User::factory()->create();
        OrderTravel::factory()->create(['user_id' => $this->userAuthenticated->id]);
        OrderTravel::factory()->create(['user_id' => $user]);

        $data = ['paginate' => false];
        $response = $this->actingAs($this->userAuthenticated , 'sanctum')->get('/api/v1/order/travels/', $data);

        $response->assertSuccessful();
        $response->assertJsonStructure(['data']);
        $response->assertJsonMissing(['current_page' => true]);
        $this->assertCount(1, $response['data']);
    }

    public function testShouldShowOrderTravel()
    {
        $orderTravel = OrderTravel::factory()->create(['user_id' => $this->userAuthenticated->id]);
        $response = $this->actingAs($this->userAuthenticated , 'sanctum')->get('/api/v1/order/travels/'. $orderTravel->id);

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

<?php

namespace Tests\Feature\app\Http\Controllers;

use App\Models\OrderTravel;
use App\Models\User;
use Illuminate\Foundation\Testing\{DatabaseTransactions};
use Mockery;
use Tests\TestCase;

class OrderTravelControllerExceptionTest extends TestCase
{
    use DatabaseTransactions;

    public function testShouldUpdateThrowExceptionWhenOrderTravelNotFound()
    {
        $user = User::factory()->create();
        $nonExistentOrderTravelId = 9999;

        $response = $this->actingAs($user, 'sanctum')
            ->putJson('/api/v1/order/travels/' . $nonExistentOrderTravelId, [
                'order_travel_status_id' => 2,
            ]);

        $mock = Mockery::mock('overload:Pusher\Pusher');
        $mock->shouldReceive('trigger')->once()->andReturn(true);

        $response->assertStatus(404);
        $response->assertJsonFragment([
            'error' => 'Pedido de viagem não encontrado. Verifique se o ID está correto.',
        ]);
    }

    public function testShouldThrowUnauthorizedExceptionIfUserCannotUpdateOrderTravel()
    {
        $userWithPermission = User::factory()->create();

        $mock = Mockery::mock('overload:Pusher\Pusher');
        $mock->shouldReceive('trigger')->once()->andReturn(true);

        $orderTravel = OrderTravel::factory()->create(['user_id' => $userWithPermission->id]);

        $response = $this->actingAs($userWithPermission, 'sanctum')
            ->putJson('/api/v1/order/travels/' . $orderTravel->id, [
                'order_travel_status_id' => 2,
            ]);

        $response->assertStatus(403);
        $response->assertJsonFragment([
            'error' => 'Você não tem permissão para editar essa solicitação de viagem.',
        ]);
    }

    public function testShouldShowThrowExceptionWhenOrderTravelNotFound()
    {
        $user = User::factory()->create();
        $nonExistentOrderTravelId = 9999;

        $response = $this->actingAs($user, 'sanctum')
            ->getJson('/api/v1/order/travels/' . $nonExistentOrderTravelId);

        $response->assertStatus(404);
        $response->assertJsonFragment([
            'error' => 'Pedido de viagem não encontrado. Verifique se o ID está correto.',
        ]);
    }

    public function testShouldThrowUnauthorizedExceptionIfUserCannotViewOrderTravel()
    {
        $userWithPermission = User::factory()->create();
        $userWithoutPermission = User::factory()->create();

        $orderTravel = OrderTravel::factory()->create(['user_id' => $userWithPermission->id]);

        $response = $this->actingAs($userWithoutPermission, 'sanctum')
            ->getJson('/api/v1/order/travels/' . $orderTravel->id);

        $response->assertStatus(403);
        $response->assertJsonFragment([
            'error' => 'Você não tem permissão para visualizar essa solicitação de viagem.',
        ]);
    }
}

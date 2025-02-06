<?php

namespace Tests\Feature\app\Http\Controllers;

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
            'message' => 'Solicitação de viagem criada com sucesso',
        ]);

        $response->assertJsonStructure([
            'message',
            'data' => [
                'name_applicant',
                'destination',
                'departure_date',
                'return_date',
                'user_id',
            ],
        ]);
    }
}

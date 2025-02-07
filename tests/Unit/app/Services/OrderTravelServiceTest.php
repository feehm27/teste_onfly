<?php

namespace Tests\Unit\app\Services;

use App\Exceptions\CancellationNotAllowedException;
use App\Models\OrderTravel;
use App\Models\User;
use App\Notifications\OrderTravelUpdatedNotification;
use App\Repositories\Contracts\OrderTravelRepositoryInterface;
use App\Services\OrderTravelService;
use Carbon\Carbon;
use Mockery;
use Tests\TestCase;

class OrderTravelServiceTest extends TestCase
{
    protected $orderTravelService;
    protected $orderTravelRepositoryMock;

    public function testFindById()
    {
        $orderTravel = OrderTravel::factory()->create();

        $this->orderTravelRepositoryMock
            ->shouldReceive('find')
            ->once()
            ->with($orderTravel->id)
            ->andReturn($orderTravel);

        $result = $this->orderTravelService->findById($orderTravel->id);

        $this->assertEquals($orderTravel->id, $result->id);
        $this->assertInstanceOf(OrderTravel::class, $result);
    }

    public function testCreateTravel()
    {
        $user = User::factory()->create();

        $data = [
            'name_applicant' => 'Teste',
            'destination' => 'São Paulo',
            'departure_date' => Carbon::now()->addDay()->format('Y-m-d H:i:s'),
            'return_date' => Carbon::now()->addDays(2)->format('Y-m-d H:i:s'),
            'user_id' => $user->id,
        ];

        $this->orderTravelRepositoryMock
            ->shouldReceive('create')
            ->once()
            ->with($data)
            ->andReturn(new OrderTravel($data));

        $orderTravel = $this->orderTravelService->createTravel($data);

        $this->assertInstanceOf(OrderTravel::class, $orderTravel);
        $this->assertEquals($orderTravel->name_applicant, 'Teste');
        $this->assertEquals($orderTravel->destination, 'São Paulo');
        $this->assertEquals($orderTravel->user_id, $user->id);
    }

    public function testGetTravelsWithFilters()
    {
        $departureDate = Carbon::now()->addDay()->format('Y-m-d');
        $returnDate = Carbon::now()->addDays(2)->format('Y-m-d');

        $filters = (object)[
            'user_id' => 1,
            'order_travel_status_id' => 2,
            'destination' => 'São Paulo',
            'departure_date' => $departureDate,
            'return_date' => $returnDate,
            'paginate' => false,
            'limit' => 10
        ];

        $travels = collect([new OrderTravel(['user_id' => 1, 'destination' => 'São Paulo'])]);

        $this->orderTravelRepositoryMock
            ->shouldReceive('where')
            ->once()
            ->with('user_id', 1)
            ->andReturnSelf();

        $this->orderTravelRepositoryMock
            ->shouldReceive('where')
            ->once()
            ->with('order_travel_status_id', 2)
            ->andReturnSelf();

        $this->orderTravelRepositoryMock
            ->shouldReceive('whereLike')
            ->once()
            ->with('destination', '%São Paulo%')
            ->andReturnSelf();

        $this->orderTravelRepositoryMock
            ->shouldReceive('get')
            ->once()
            ->andReturn($travels);

        $this->orderTravelRepositoryMock
            ->shouldReceive('where')
            ->once()
            ->with('departure_date', $departureDate, '>')
            ->andReturnSelf();

        $this->orderTravelRepositoryMock
            ->shouldReceive('where')
            ->once()
            ->with('return_date', $returnDate, '<')
            ->andReturnSelf();

        $this->orderTravelRepositoryMock
            ->shouldReceive('orderBy')
            ->once()
            ->with('departure_date')
            ->andReturnSelf();

        $result = $this->orderTravelService->getTravelsWithFilters($filters);
        $this->assertCount(1, $result);
    }

    public function testUpdateTravelStatus()
    {
        $orderTravel = OrderTravel::factory()->create(
            ['order_travel_status_id' => 1,
                'updated_at' => Carbon::now()]
        );

        $inputs = (object)[
            'id' => $orderTravel->id,
            'order_travel_status_id' => 2
        ];

        $this->orderTravelRepositoryMock
            ->shouldReceive('find')
            ->once()
            ->with($orderTravel->id)
            ->andReturn($orderTravel);

        $user = Mockery::mock(User::class);
        $this->actingAs($user);

        $user->shouldReceive('notify')
            ->once()
            ->with(Mockery::type(OrderTravelUpdatedNotification::class))
            ->andReturn(true);

        $updatedOrderTravel = $this->orderTravelService->updateTravelStatus($inputs);

        $this->assertEquals(2, $updatedOrderTravel->order_travel_status_id);
    }

    public function testShouldThrowCancellationNotAllowedException()
    {
        $orderTravel = OrderTravel::factory()->create([
            'order_travel_status_id' => OrderTravelService::APPROVED_STATUS,
            'updated_at' => Carbon::now()->subDays(2)
        ]);

        $this->orderTravelRepositoryMock
            ->shouldReceive('find')
            ->once()
            ->with($orderTravel->id)
            ->andReturn($orderTravel);

        $this->expectException(CancellationNotAllowedException::class);

        $inputs = (object)[
            'id' => $orderTravel->id,
            'order_travel_status_id' => OrderTravelService::CANCELED_STATUS,
        ];

        $this->orderTravelService->updateTravelStatus($inputs);
    }

    public function testCancelTravelWithin24Hours()
    {
        $orderTravel = OrderTravel::factory()->create([
            'order_travel_status_id' => OrderTravelService::APPROVED_STATUS,
            'updated_at' => Carbon::now()->subHours(10)
        ]);

        $inputs = (object)[
            'id' => $orderTravel->id,
            'order_travel_status_id' => OrderTravelService::CANCELED_STATUS,
        ];

        $this->orderTravelRepositoryMock
            ->shouldReceive('find')
            ->once()
            ->with($orderTravel->id)
            ->andReturn($orderTravel);

        $user = Mockery::mock(User::class);
        $this->actingAs($user);

        $user->shouldReceive('notify')
            ->once()
            ->with(Mockery::type(OrderTravelUpdatedNotification::class))
            ->andReturn(true);

        $updatedOrderTravel = $this->orderTravelService->updateTravelStatus($inputs);

        $this->assertEquals(OrderTravelService::CANCELED_STATUS, $updatedOrderTravel->order_travel_status_id);
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->orderTravelRepositoryMock = Mockery::mock(OrderTravelRepositoryInterface::class);
        $this->orderTravelService = new OrderTravelService($this->orderTravelRepositoryMock);
    }
}

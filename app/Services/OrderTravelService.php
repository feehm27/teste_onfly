<?php

namespace App\Services;

use App\Exceptions\CancellationNotAllowedException;
use App\Models\OrderTravel;
use App\Repositories\Contracts\OrderTravelRepositoryInterface;
use Carbon\Carbon;
use Exception;
use Symfony\Component\HttpFoundation\Response;

class OrderTravelService
{
    public const APPROVED_STATUS = 2;
    public const CANCELED_STATUS = 3;

    public function __construct(protected OrderTravelRepositoryInterface $travelRepository)
    {
    }

    public function findById(int $travelId)
    {
        return $this->travelRepository->find($travelId);
    }

    public function getTravelsWithFilters(object $filters)
    {
        $this->applyFilters($filters);
        $this->travelRepository->orderBy('departure_date');

        if ($filters->paginate) {
            return $this->travelRepository->paginate($filters->limit);
        } else {
            return $this->travelRepository->get();
        }
    }

    private function applyFilters(object $filters): void
    {
        $this->travelRepository->where('user_id', $filters->user_id);

        if (isset($filters->order_travel_status_id)) {
            $this->travelRepository->where('order_travel_status_id', $filters->order_travel_status_id);
        }

        if (isset($filters->destination)) {
            $search = "%{$filters->destination}%";
            $this->travelRepository->whereLike('destination', $search);
        }

        $this->applyFilterBasedOnPeriod($filters);
    }

    private function applyFilterBasedOnPeriod(object $filters): void
    {
        $hasDepartureDate = isset($filters->departure_date);
        $hasReturnDate = isset($filters->return_date);

        if ($hasDepartureDate && $hasReturnDate) {
            $this->travelRepository->where('departure_date', $filters->departure_date, '>');
            $this->travelRepository->where('return_date', $filters->return_date, '<');
        }

        if ($hasDepartureDate && !$hasReturnDate) {
            $this->travelRepository->where('departure_date', $filters->departure_date, '>');
        }

        if (!$hasDepartureDate && $hasReturnDate) {
            $this->travelRepository->where('return_date', $filters->return_date, '<');
        }
    }

    public function createTravel(array $inputs)
    {
        return $this->travelRepository->create($inputs);
    }

    /**
     * @throws Exception
     */
    public function updateTravelStatus(object $inputs): OrderTravel
    {
        $orderTravel = $this->travelRepository->find($inputs->id);
        $this->checkIfCanBeCancelled($inputs->order_travel_status_id, $orderTravel);

        $orderTravel->order_travel_status_id = $inputs->order_travel_status_id;
        $orderTravel->save();

        return $orderTravel;
    }

    /**
     * @throws Exception
     */
    private function checkIfCanBeCancelled(int $orderTravelStatusId, OrderTravel $orderTravel): void
    {
        if ($orderTravelStatusId == self::CANCELED_STATUS && $orderTravel->order_travel_status_id == self::APPROVED_STATUS) {

            $providedDate = Carbon::parse($orderTravel->updated_at);
            $currentDate = Carbon::now();

            if ($providedDate->diffInHours($currentDate) > 24) {
                throw new CancellationNotAllowedException();
            }
        }
    }
}

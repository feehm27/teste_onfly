<?php

namespace App\Services;

use App\Models\OrderTravel;
use App\Repositories\Contracts\OrderTravelRepositoryInterface;
use Exception;

class OrderTravelService
{
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

    public function createTravel(array $inputs)
    {
        //TODO
        //Obter identificador do usuario apos autenticação
        $inputs['user_id'] = 1;
        return $this->travelRepository->create($inputs);
    }

    public function updateTravelStatus(array $inputs): OrderTravel
    {
        $orderTravelId = $inputs['id'];
        $params = [
            'order_travel_status_id' => $inputs['order_travel_status_id']
        ];

        $updateSuccessful = $this->travelRepository->updateById($orderTravelId, $params);

        return $updateSuccessful
            ? $this->travelRepository->find($orderTravelId)
            : throw new Exception('Não foi possível atualizar o status da viagem. Por favor, entre em contato com o suporte.');
    }

    private function applyFilters(object $filters): void
    {
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
}

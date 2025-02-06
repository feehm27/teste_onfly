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

    public function getTravelsWithFilters(array $filters)
    {
        if (isset($filters['travel_status_id'])) {
            $this->travelRepository->where('travel_status_id', $filters['travel_status_id']);
        }

        if ($filters['paginate']) {
            return $this->travelRepository->getPaginate($filters['limit']);
        }

        return $this->travelRepository->get();
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
}

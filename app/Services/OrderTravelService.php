<?php

namespace App\Services;

use App\Repositories\Contracts\OrderTravelRepositoryInterface;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class OrderTravelService
{
    public function __construct(protected OrderTravelRepositoryInterface $travelRepository)
    {
    }

    public function findTravel(int $travelId)
    {
        return $this->travelRepository->find($travelId);
    }

    public function getListOfTravels(array $filters)
    {
        if (isset($filters['travel_status_id'])) {
            $this->travelRepository->where('travel_status_id', $filters['travel_status_id']);
        }

        if ($filters['paginate']) {
            return $this->travelRepository->getPaginate($filters['limit']);
        }

        return $this->travelRepository->get();
    }

    public function create(array $inputs)
    {
        //TODO
        //Obter identificador do usuario apos autenticação
        $inputs['user_id'] = 1;
        return $this->travelRepository->create($inputs);
    }

    public function updateStatusId(array $inputs): ?bool
    {
        $travel = $this->travelRepository->find($inputs['id']);

        if (Gate::denies('update-status-travel', $travel)) {
            abort(Response::HTTP_UNAUTHORIZED, 'Este usuário não possui autorização para alterar o pedido.');
        }

        return $this->travelRepository->updateById($inputs['id'], $inputs['travel_status_id']);
    }
}

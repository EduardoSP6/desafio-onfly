<?php

namespace App\Http\Controllers;

use Application\Exception\InvalidTravelOrderStatusException;
use Application\Exception\OperationNotPermittedException;
use Application\UseCases\TravelOrder\Approve\ApproveTravelOrderUseCase;
use Application\UseCases\TravelOrder\Cancel\CancelTravelOrderUseCase;
use Illuminate\Http\JsonResponse;
use Infrastructure\Persistence\Repositories\TravelOrderEloquentRepository;

class TravelOrderStatusController extends Controller
{
    private TravelOrderEloquentRepository $travelOrderEloquentRepository;

    public function __construct(TravelOrderEloquentRepository $travelOrderEloquentRepository)
    {
        $this->travelOrderEloquentRepository = $travelOrderEloquentRepository;
    }

    public function approve(string $orderId): JsonResponse
    {
        try {
            (new ApproveTravelOrderUseCase($this->travelOrderEloquentRepository))->execute($orderId);

            return response()->json(["message" => "Pedido aprovado com sucesso"], 200);
        } catch (OperationNotPermittedException $e) {
            abort(403, $e->getMessage());
        } catch (InvalidTravelOrderStatusException $e) {
            abort(400, $e->getMessage());
        }
    }

    public function cancel(string $orderId): JsonResponse
    {
        try {
            (new CancelTravelOrderUseCase($this->travelOrderEloquentRepository))->execute($orderId);

            return response()->json(["message" => "Pedido cancelado com sucesso"], 200);
        } catch (OperationNotPermittedException $e) {
            abort(403, $e->getMessage());
        } catch (InvalidTravelOrderStatusException $e) {
            abort(400, $e->getMessage());
        }
    }
}

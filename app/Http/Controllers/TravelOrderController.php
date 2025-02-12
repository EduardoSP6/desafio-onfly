<?php

namespace App\Http\Controllers;

use App\Http\Requests\TravelOrders\CreateTravelOrderRequest;
use App\Http\Resources\TravelOrderResource;
use Application\Exception\DuplicatedTravelOrderException;
use Application\UseCases\TravelOrder\Create\CreateTravelOrderInputDto;
use Application\UseCases\TravelOrder\Create\CreateTravelOrderUseCase;
use Application\UseCases\TravelOrder\Find\FindTravelOrderUseCase;
use DateTimeImmutable;
use Exception;
use Illuminate\Http\JsonResponse;
use Infrastructure\Persistence\Repositories\TravelOrderEloquentRepository;
use Infrastructure\Persistence\Repositories\UserEloquentRepository;

class TravelOrderController extends Controller
{
    private TravelOrderEloquentRepository $travelOrderEloquentRepository;
    private UserEloquentRepository $userEloquentRepository;

    public function __construct(TravelOrderEloquentRepository $travelOrderEloquentRepository, UserEloquentRepository $userEloquentRepository)
    {
        $this->travelOrderEloquentRepository = $travelOrderEloquentRepository;
        $this->userEloquentRepository = $userEloquentRepository;
    }

    public function store(CreateTravelOrderRequest $request): JsonResponse
    {
        try {
            $inputDto = new CreateTravelOrderInputDto(
                destination: $request->validated('destination'),
                departureDate: new DateTimeImmutable($request->validated('departure_date')),
                returnDate: new DateTimeImmutable($request->validated('return_date')),
            );

            $createTravelOrderUseCase = new CreateTravelOrderUseCase(
                $this->travelOrderEloquentRepository,
                $this->userEloquentRepository
            );

            $createTravelOrderUseCase->execute($inputDto);

            return response()->json([], 201);
        } catch (DuplicatedTravelOrderException $e) {
            abort(400, $e->getMessage());
        } catch (Exception $e) {
            abort(422, $e->getMessage());
        }
    }

    public function show(string $orderId): TravelOrderResource
    {
        $travelOrder = (new FindTravelOrderUseCase($this->travelOrderEloquentRepository))
            ->execute($orderId);

        abort_if(!$travelOrder, 404, "Registro não encontrado");

        return new TravelOrderResource($travelOrder);
    }
}

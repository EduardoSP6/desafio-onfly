<?php

namespace App\Http\Controllers;

use App\Http\Requests\TravelOrders\CreateTravelOrderRequest;
use App\Http\Resources\TravelOrderResource;
use Application\Exception\DuplicatedTravelOrderException;
use Application\UseCases\TravelOrder\Create\CreateTravelOrderInputDto;
use Application\UseCases\TravelOrder\Create\CreateTravelOrderUseCase;
use Application\UseCases\TravelOrder\Find\FindTravelOrderUseCase;
use Application\UseCases\TravelOrder\ListAll\ListTravelOrdersUseCase;
use DateTimeImmutable;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Infrastructure\Persistence\Repositories\TravelOrderEloquentRepository;
use OpenApi\Annotations as OA;

class TravelOrderController extends Controller
{
    private TravelOrderEloquentRepository $travelOrderEloquentRepository;

    public function __construct(TravelOrderEloquentRepository $travelOrderEloquentRepository)
    {
        $this->travelOrderEloquentRepository = $travelOrderEloquentRepository;
    }

    /**
     * @OA\Get(
     *     path="/api/v1/travel-orders",
     *     operationId="listAllCategories",
     *     tags={"Travel Orders"},
     *     summary="Lista todas os pedidos de viagem.",
     *     description="Lista todas os pedidos de viagem pertencentes ao usuário logado.",
     *     security={{"BearerAuth":{}}},
     *
     *     @OA\Response(
     *          response="200",
     *          description="OK",
     *          @OA\JsonContent(
     *              type="array",
     *              @OA\Items(ref="#/components/schemas/TravelOrderResource")
     *          ),
     *    ),
     * )
     */
    public function index(): AnonymousResourceCollection
    {
        $travelOrders = (new ListTravelOrdersUseCase($this->travelOrderEloquentRepository))->execute();

        return TravelOrderResource::collection($travelOrders);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/travel-orders",
     *     operationId="storeTravelOrder",
     *     tags={"Travel Orders"},
     *     summary="Cria um novo pedido de viagem.",
     *     description="Cria um novo pedido de viagem.",
     *     security={{"BearerAuth":{}}},
     *
     *     @OA\RequestBody(
     *          required=true,
     *          @OA\MediaType(
     *              mediaType="application/x-www-form-urlencoded",
     *              @OA\Schema(
     *                  type="object",
     *                  required={"destination", "departure_date", "return_date"},
     *                  @OA\Property(
     *                      property="destination",
     *                      type="string",
     *                      description="Local de destino",
     *                      maxLength=200,
     *                  ),
     *                  @OA\Property(
     *                      property="departure_date",
     *                      type="string",
     *                      description="Data da partida no formato YYYY-MM-DD",
     *                  ),
     *                  @OA\Property(
     *                      property="return_date",
     *                      type="string",
     *                      description="Data do retorno no formato YYYY-MM-DD",
     *                  ),
     *              ),
     *          ),
     *     ),
     *
     *     @OA\Response(
     *          response="201",
     *          description="Record created successfully",
     *     ),
     *
     *     @OA\Response(
     *          response="422",
     *          description="Unprocessable Entity",
     *     ),
     *
     *     @OA\Response(
     *          response="400",
     *          description="Duplicated order",
     *     ),
     * )
     */
    public function store(CreateTravelOrderRequest $request): JsonResponse
    {
        try {
            $inputDto = new CreateTravelOrderInputDto(
                destination: $request->validated('destination'),
                departureDate: new DateTimeImmutable($request->validated('departure_date')),
                returnDate: new DateTimeImmutable($request->validated('return_date')),
            );

            $createTravelOrderUseCase = new CreateTravelOrderUseCase(
                $this->travelOrderEloquentRepository
            );

            $createTravelOrderUseCase->execute($inputDto);

            return response()->json([], 201);
        } catch (DuplicatedTravelOrderException $e) {
            abort(400, $e->getMessage());
        } catch (Exception $e) {
            abort(422, $e->getMessage());
        }
    }

    /**
     * @OA\Get(
     *     path="/api/v1/travel-orders/{orderId}",
     *     operationId="showTravelOrder",
     *     tags={"Travel Orders"},
     *     summary="Consulta um pedido de viagem.",
     *     description="Consulta um pedido de viagem por ID do pedido.",
     *     security={{"BearerAuth":{}}},
     *
     *     @OA\Parameter(
     *          in="path",
     *          name="orderId",
     *          required=true,
     *          description="ID do pedido",
     *          @OA\Schema(type="string")
     *     ),
     *
     *     @OA\Response(
     *          response="200",
     *          description="OK",
     *          @OA\JsonContent(
     *              type="object",
     *              ref="#/components/schemas/TravelOrderResource"
     *          ),
     *    ),
     *
     *     @OA\Response(
     *          response="404",
     *          description="Record not found",
     *    ),
     * )
     */
    public function show(string $orderId): TravelOrderResource
    {
        $travelOrder = (new FindTravelOrderUseCase($this->travelOrderEloquentRepository))
            ->execute($orderId);

        abort_if(!$travelOrder, 404, "Registro não encontrado");

        return new TravelOrderResource($travelOrder);
    }
}

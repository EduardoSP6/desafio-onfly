<?php

namespace App\Http\Controllers;

use Application\Exception\CancellationDeadlineApprovedTravelOrderExceededException;
use Application\Exception\InvalidTravelOrderStatusException;
use Application\Exception\OperationNotPermittedException;
use Application\UseCases\TravelOrder\Approve\ApproveTravelOrderUseCase;
use Application\UseCases\TravelOrder\Cancel\CancelTravelOrderUseCase;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Infrastructure\Persistence\Repositories\TravelOrderEloquentRepository;
use OpenApi\Annotations as OA;

class TravelOrderStatusController extends Controller
{
    private TravelOrderEloquentRepository $travelOrderEloquentRepository;

    public function __construct(TravelOrderEloquentRepository $travelOrderEloquentRepository)
    {
        $this->travelOrderEloquentRepository = $travelOrderEloquentRepository;
    }

    /**
     * @OA\Patch(
     *     path="/api/v1/travel-orders/{orderId}/approve",
     *     operationId="approveTravelOrder",
     *     tags={"Travel Orders"},
     *     summary="Aprova um pedido de viagem.",
     *     description="Aprova um pedido de viagem. Esta ação não pode ser feita pelo usuário que criou o registro.",
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
     *              @OA\Property(
     *                  property="message",
     *                  type="string",
     *                  description="Pedido aprovado com sucesso"
     *              ),
     *          ),
     *     ),
     *
     *     @OA\Response(
     *          response="403",
     *          description="Forbidden",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(
     *                  property="message",
     *                  type="string",
     *                  description="Você não possui permissão para alterar status do pedido."
     *              ),
     *          ),
     *     ),
     *
     *     @OA\Response(
     *          response="400",
     *          description="Invalid travel order status",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(
     *                  property="message",
     *                  type="string",
     *                  description="O status do pedido não pode ser alterado."
     *              ),
     *          ),
     *     ),
     *
     *     @OA\Response(
     *          response="500",
     *          description="Internal server error",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(
     *                  property="message",
     *                  type="string",
     *                  description="Erro ao aprovar pedido de viagem."
     *              ),
     *          ),
     *     ),
     * )
     */
    public function approve(string $orderId): JsonResponse
    {
        try {
            (new ApproveTravelOrderUseCase($this->travelOrderEloquentRepository))->execute($orderId);

            return response()->json(["message" => "Pedido aprovado com sucesso"], 200);
        } catch (OperationNotPermittedException $e) {
            return response()->json(["message" => $e->getMessage()], 403);
        } catch (InvalidTravelOrderStatusException|CancellationDeadlineApprovedTravelOrderExceededException $e) {
            return response()->json(["message" => $e->getMessage()], 400);
        } catch (Exception $e) {
            Log::error("Error to approve a travel order.", [
                'class' => get_class($this),
                'method' => 'approve',
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json(["message" => "Erro ao aprovar pedido de viagem."], 500);
        }
    }

    /**
     * @OA\Patch(
     *     path="/api/v1/travel-orders/{orderId}/cancel",
     *     operationId="cancelTravelOrder",
     *     tags={"Travel Orders"},
     *     summary="Cancela um pedido de viagem.",
     *     description="Cancela um pedido de viagem. Esta ação não pode ser feita pelo usuário que criou o registro.",
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
     *              @OA\Property(
     *                  property="message",
     *                  type="string",
     *                  description="Pedido cancelado com sucesso"
     *              ),
     *          ),
     *     ),
     *
     *     @OA\Response(
     *          response="403",
     *          description="Forbidden",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(
     *                  property="message",
     *                  type="string",
     *                  description="Você não possui permissão para alterar status do pedido."
     *              ),
     *          ),
     *     ),
     *
     *     @OA\Response(
     *          response="400",
     *          description="Invalid travel order status",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(
     *                  property="message",
     *                  type="string",
     *                  description="O status do pedido não pode ser alterado."
     *              ),
     *          ),
     *     ),
     *
     *     @OA\Response(
     *          response="500",
     *          description="Internal server error",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(
     *                  property="message",
     *                  type="string",
     *                  description="Erro ao cancelar pedido de viagem."
     *              ),
     *          ),
     *     ),
     * )
     */
    public function cancel(string $orderId): JsonResponse
    {
        try {
            (new CancelTravelOrderUseCase($this->travelOrderEloquentRepository))->execute($orderId);

            return response()->json(["message" => "Pedido cancelado com sucesso"], 200);
        } catch (OperationNotPermittedException $e) {
            return response()->json(["message" => $e->getMessage()], 403);
        } catch (InvalidTravelOrderStatusException $e) {
            return response()->json(["message" => $e->getMessage()], 400);
        } catch (Exception $e) {
            Log::error("Error to approve a travel order.", [
                'class' => get_class($this),
                'method' => 'cancel',
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json(["message" => "Erro ao cancelar pedido de viagem."], 500);
        }
    }
}

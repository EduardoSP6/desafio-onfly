<?php

namespace App\Http\Resources;

use Domain\Core\Entity\TravelOrder;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="TravelOrderResource",
 *     type="object",
 *
 *     @OA\Property(
 *          property="uuid",
 *          type="string",
 *          description="Uuid do pedido de viagem"
 *     ),
 *
 *     @OA\Property(
 *          property="orderId",
 *          type="string",
 *          description="ID do pedido"
 *     ),
 *
 *     @OA\Property(
 *          property="userName",
 *          type="string",
 *          description="Nome do usuário"
 *     ),
 *
 *     @OA\Property(
 *          property="destination",
 *          type="string",
 *          description="Local de destino"
 *     ),
 *
 *     @OA\Property(
 *          property="departureDate",
 *          type="string",
 *          description="Data da partida no formato DD/MM/YYYY"
 *     ),
 *
 *     @OA\Property(
 *          property="returnDate",
 *          type="string",
 *          description="Data do retorno no formato DD/MM/YYYY"
 *     ),
 *
 *     @OA\Property(
 *          property="status",
 *          type="string",
 *          description="Status do pedido",
 *          enum={"requested", "approved", "cancelled"},
 *     ),
 *
 *     @OA\Property(
 *          property="statusDescription",
 *          type="string",
 *          description="Descrição do status",
 *          enum={"Solicitado", "Aprovado", "Cancelado"},
 *     ),
 * )
 */
class TravelOrderResource extends JsonResource
{
    private TravelOrder $travelOrder;

    public function __construct(TravelOrder $travelOrder)
    {
        parent::__construct($this);

        $this->travelOrder = $travelOrder;
    }

    public function toArray($request): array
    {
        return [
            'uuid' => $this->travelOrder->getUuid()->value(),
            'orderId' => $this->travelOrder->getOrderId()->value(),
            'userName' => $this->travelOrder->getUser()->getName(),
            'destination' => $this->travelOrder->getDestination(),
            'departureDate' => $this->travelOrder->getDepartureDate()->format('d/m/Y'),
            'returnDate' => $this->travelOrder->getReturnDate()->format('d/m/Y'),
            'status' => $this->travelOrder->getStatus()->value,
            'statusDescription' => $this->travelOrder->getStatus()->getDescription(),
        ];
    }
}

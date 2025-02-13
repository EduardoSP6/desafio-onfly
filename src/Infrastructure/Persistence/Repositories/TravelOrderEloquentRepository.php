<?php

namespace Infrastructure\Persistence\Repositories;

use Application\Exception\DuplicatedTravelOrderException;
use Application\Interfaces\TravelOrderRepository;
use DateTimeImmutable;
use Domain\Core\Entity\TravelOrder;
use Domain\Core\Enum\TravelOrderStatus;
use Domain\Shared\ValueObject\OrderId;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Infrastructure\Persistence\DataMappers\TravelOrderDataMapper;
use Infrastructure\Persistence\Models\TravelOrder as TravelOrderModel;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class TravelOrderEloquentRepository implements TravelOrderRepository
{

    public function create(TravelOrder $travelOrder): void
    {
        $exist = $this->checkIfTravelOrderExists(
            $travelOrder->getDepartureDate(),
            $travelOrder->getReturnDate()
        );

        throw_if(
            $exist,
            new DuplicatedTravelOrderException("Já existe um registro neste período")
        );

        $travelOrderModel = (new TravelOrderDataMapper())->toPersistence($travelOrder);
        $travelOrderModel->save();
    }

    public function updateStatus(TravelOrder $travelOrder): void
    {
        TravelOrderModel::query()
            ->where('uuid', '=', $travelOrder->getUuid()->value())
            ->update([
                'status' => $travelOrder->getStatus()->value
            ]);
    }

    public function findByOrderId(OrderId $orderId): ?TravelOrder
    {
        $travelOrderModel = TravelOrderModel::query()
            ->firstWhere('order_id', '=', $orderId->value());

        if (!$travelOrderModel) return null;

        return (new TravelOrderDataMapper())->toDomain($travelOrderModel);
    }

    public function listAll(): array
    {
        $databaseCollection = QueryBuilder::for(TravelOrderModel::class)
            ->allowedFilters([
                AllowedFilter::exact('status'),
                AllowedFilter::partial('destination'),
                AllowedFilter::callback('period', function (Builder $query, array $value) {
                    $query->whereDate('departure_date', '>=', $value[0])
                        ->whereDate('return_date', '<=', $value[1]);
                }),
            ])
            ->get();

        if (count($databaseCollection) === 0) return [];

        $travelOrders = [];
        /** @var TravelOrderModel $travelOrderModel */
        foreach ($databaseCollection as $travelOrderModel) {
            $travelOrders[] = (new TravelOrderDataMapper())->toDomain($travelOrderModel);
        }

        return $travelOrders;
    }

    protected function checkIfTravelOrderExists(DateTimeImmutable $departureDate, DateTimeImmutable $returnDate): bool
    {
        return TravelOrderModel::query()
            ->whereDate('departure_date', '>=', $departureDate->format('Y-m-d'))
            ->whereDate('return_date', '<=', $returnDate->format('Y-m-d'))
            ->whereIn('status', [
                TravelOrderStatus::REQUESTED->value,
                TravelOrderStatus::APPROVED->value
            ])
            ->exists();
    }
}

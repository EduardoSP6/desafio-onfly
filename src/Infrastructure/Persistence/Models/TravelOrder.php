<?php

namespace Infrastructure\Persistence\Models;

use DateTimeImmutable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Infrastructure\Traits\TenantModels;

/**
 * @property int $id
 * @property string $uuid
 * @property string $order_id
 * @property int $user_id
 * @property string $destination
 * @property DateTimeImmutable $departure_date
 * @property DateTimeImmutable $return_date
 * @property string $status
 * @property DateTimeImmutable $created_at
 * @property DateTimeImmutable|null $updated_at
 * @property User $user
 */
class TravelOrder extends Model
{
    use TenantModels;

    protected $table = "travel_orders";

    protected $fillable = [
        'uuid',
        'order_id',
        'user_id',
        'destination',
        'departure_date',
        'return_date',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'departure_date' => 'immutable_datetime',
            'return_date' => 'immutable_datetime',
            'created_at' => 'immutable_datetime',
            'updated_at' => 'immutable_datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

<?php

namespace Infrastructure\Persistence\DataMappers;

use Domain\Shared\Entity\BaseEntity;
use Illuminate\Database\Eloquent\Model;

abstract class BaseDataMapper
{
    abstract public function toDomain(Model $model);

    abstract public function toPersistence(BaseEntity $entity);
}

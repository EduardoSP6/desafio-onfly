<?php

namespace Infrastructure\Persistence\Repositories;

use Application\Interfaces\UserRepository;
use Domain\Core\Entity\User;
use Infrastructure\Persistence\DataMappers\UserDataMapper;
use Infrastructure\Persistence\Models\User as UserModel;

class UserEloquentRepository implements UserRepository
{
    public function findById(int $id): ?User
    {
        $userModel = UserModel::query()->find($id);

        if (!$userModel) return null;

        return (new UserDataMapper())->toDomain($userModel);
    }
}

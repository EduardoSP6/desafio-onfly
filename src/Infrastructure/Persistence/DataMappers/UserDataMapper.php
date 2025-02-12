<?php

namespace Infrastructure\Persistence\DataMappers;

use Domain\Core\Entity\User;
use Domain\Shared\Entity\BaseEntity;
use Domain\Shared\ValueObject\Uuid;
use Illuminate\Database\Eloquent\Model;
use Infrastructure\Persistence\Models\User as UserModel;

class UserDataMapper extends BaseDataMapper
{

    /**
     * @param UserModel $model
     * @return User
     */
    public function toDomain(Model $model): User
    {
        return new User(
            uuid: new Uuid($model->uuid),
            name: $model->name,
            email: $model->email,
            isAdmin: $model->is_admin,
            createdAt: $model->created_at,
            updatedAt: $model->updated_at
        );
    }

    /**
     * @param User $entity
     * @return UserModel
     */
    public function toPersistence(BaseEntity $entity): UserModel
    {
        $userModel = new UserModel();
        $userModel->uuid = $entity->getUuid()->value();
        $userModel->name = $entity->getName();
        $userModel->email = $entity->getEmail();
        $userModel->is_admin = $entity->isAdmin();

        return $userModel;
    }
}

<?php

namespace Application\Interfaces;

use Domain\Core\Entity\User;

interface UserRepository
{
    public function findById(int $id): ?User;
}

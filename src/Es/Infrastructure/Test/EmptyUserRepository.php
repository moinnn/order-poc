<?php
declare(strict_types=1);

namespace App\Infrastructure\Test;

use App\Domain\Entity\User;
use App\Domain\Repository\UserRepository;

class EmptyUserRepository implements UserRepository
{
    public function save(User $user): void
    {
    }

    public function get(string $id): ?User
    {
        return null;
    }

}
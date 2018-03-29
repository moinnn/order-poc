<?php
declare(strict_types=1);

namespace App\Infrastructure\Test;

use App\Domain\Entity\User;
use App\Domain\Repository\UserRepository;

class InMemoryUserRepository implements UserRepository
{
    /**
     * @var User[]
     */
    private $users = [];

    public function save(User $user): void
    {
        $this->users[$user->aggregateId()] = $user;
    }

    public function get(string $id): ?User
    {
        return $this->users[$id];
    }

}
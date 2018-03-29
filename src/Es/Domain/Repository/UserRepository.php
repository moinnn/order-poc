<?php
declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Entity\User;

interface UserRepository
{
    public function save(User $user): void;
    public function get(string $id): ?User;
}
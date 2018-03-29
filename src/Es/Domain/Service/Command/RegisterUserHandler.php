<?php
declare(strict_types=1);

namespace App\Domain\Service\Command;

use App\Domain\Entity\User;
use App\Domain\Repository\UserRepository;

final class RegisterUserHandler
{
    private $repository;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(RegisterUser $registerUser): void
    {
        $user = User::registerWithData($registerUser->id(), $registerUser->email(), $registerUser->password());
        $this->repository->save($user);
    }
}
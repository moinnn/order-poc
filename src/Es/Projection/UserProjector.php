<?php
declare(strict_types=1);

namespace App\Projection;

use App\Domain\Event\EmailChanged;
use App\Domain\Event\UserRegistered;

class UserProjector
{
    private $pdo;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function onUserRegistered(UserRegistered $userRegistered): void
    {
        $query = $this->pdo->prepare('INSERT INTO `read_users` SET email = ?, password = ?, id = ?');
        $query->bindValue(1, $userRegistered->email());
        $query->bindValue(2, $userRegistered->password());
        $query->bindValue(3, $userRegistered->aggregateId());
        $query->execute();

    }

    public function onEmailChanged(EmailChanged $emailChanged): void
    {
        $query = $this->pdo->prepare('UPDATE `read_users` SET email = ? WHERE id = ?');
        $query->bindValue(1, $emailChanged->email());
        $query->bindValue(2, $emailChanged->aggregateId());
        $query->execute();
    }
}
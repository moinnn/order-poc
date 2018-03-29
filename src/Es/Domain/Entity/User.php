<?php
declare(strict_types=1);

namespace App\Domain\Entity;

use App\Domain\Event\EmailChanged;
use App\Domain\Event\UserRegistered;
use Prooph\EventSourcing\AggregateChanged;
use Prooph\EventSourcing\AggregateRoot;

class User extends AggregateRoot
{
    private $id;
    private $email;
    private $password;

    protected function aggregateId(): string
    {
        return $this->id;
    }

    protected function apply(AggregateChanged $event): void
    {
        switch (get_class($event)) {
            case UserRegistered::class:
                /** @var UserRegistered $event */
                $this->id = $event->aggregateId();
                $this->email = $event->email();
                $this->password = $event->password();
                break;
            case EmailChanged::class:
                /** @var EmailChanged $event */
                $this->id = $event->aggregateId();
                $this->email = $event->email();
                break;
        }
    }

    public function changeEmail($newEmail): void
    {
        if ($this->email === $newEmail) {
            return;
        }
        $this->recordThat(EmailChanged::occur($this->id, [
            'email' => $newEmail
        ]));
    }

    public static function registerWithData(string $id, string $email, string $password): self
    {
        $obj = new self;
        $obj->recordThat(UserRegistered::occur($id, [
            'email' => $email,
            'password' => $password
        ]));
        return $obj;
    }
}

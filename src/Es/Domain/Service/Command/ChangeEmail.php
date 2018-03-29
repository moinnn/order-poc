<?php
declare(strict_types=1);

namespace App\Domain\Service\Command;

use Prooph\Common\Messaging\Command;
use Prooph\Common\Messaging\PayloadTrait;

class ChangeEmail extends Command
{
    use PayloadTrait;

    public function id(): string
    {
        return $this->payload()['id'];
    }

    public function email(): string
    {
        return $this->payload()['email'];
    }

    protected function setPayload(array $payload): void
    {
        // TODO: Implement setPayload() method.
    }

    public function payload(): array
    {
        // TODO: Implement payload() method.
    }

}
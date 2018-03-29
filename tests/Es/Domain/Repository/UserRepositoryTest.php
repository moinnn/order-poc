<?php
declare(strict_types=1);

namespace tests\App\Domain\Service\Command;

use App\Domain\Entity\User;
use App\Infrastructure\Test\EmptyUserRepository;
use App\Infrastructure\Test\InMemoryUserRepository;
use App\Infrastructure\Test\NotAvailableRepository;
use PHPUnit\Framework\TestCase;

class UserRepositoryTest extends TestCase
{
    public function testGet()
    {
        $userRepository = new InMemoryUserRepository();
        $user = User::registerWithData('20', 'user@email.com','testpass');
        $userRepository->save($user);

        self::assertNotNull($userRepository->get($user->aggregateId()));
        self::assertInstanceOf(User::class, $userRepository->get($user->aggregateId()));
    }

    public function testSave()
    {
        $userRepository = new InMemoryUserRepository();
        $user = User::registerWithData('20', 'user@email.com','testpass');
        $userRepository->save($user);
        self::assertTrue(true);
    }

    public function testGetEmptyRepository()
    {
        $userRepository = new EmptyUserRepository();
        $user = User::registerWithData('20', 'user@email.com','testpass');
        $userRepository->save($user);
        self::assertNull($userRepository->get('20'));
    }

    public function testGetNotAvailableRepository()
    {
        self::expectException(\Exception::class);

        $userRepository = new NotAvailableRepository();
        $user = User::registerWithData('20', 'user@email.com','testpass');
        $userRepository->save($user);
        $userRepository->get('20');
    }
}
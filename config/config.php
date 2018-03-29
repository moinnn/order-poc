<?php

namespace {
    use App\Domain\Service\Command\ChangeEmail;
    use App\Domain\Service\Command\ChangeEmailHandler;
    use App\Domain\Service\Command\RegisterUser;
    use App\Domain\Service\Command\RegisterUserHandler;
    use App\Domain\Event\EmailChanged;
    use App\Domain\Event\UserRegistered;
    use App\Infrastructure\UserRepository;
    use App\Projection\UserProjector;
    use Prooph\Common\Event\ProophActionEventEmitter;
    use Prooph\Common\Messaging\FQCNMessageFactory;
    use Prooph\EventStore\ActionEventEmitterEventStore;
    use Prooph\EventStore\Pdo\MySqlEventStore;
    use Prooph\EventStore\Pdo\PersistenceStrategy\MySqlAggregateStreamStrategy;
    use Prooph\EventStore\Pdo\Projection\MySqlProjectionManager;
    use Prooph\EventStoreBusBridge\EventPublisher;
    use Prooph\ServiceBus\CommandBus;
    use Prooph\ServiceBus\EventBus;
    use Prooph\ServiceBus\Plugin\Router\CommandRouter;
    use Prooph\ServiceBus\Plugin\Router\EventRouter;
    use Prooph\SnapshotStore\Pdo\PdoSnapshotStore;

    require dirname(dirname(__FILE__)) . '/vendor/autoload.php';

    $pdo = new PDO('mysql:dbname=homestead;host=127.0.0.1', 'homestead', 'secret');
    $eventStore = new MySqlEventStore(new FQCNMessageFactory(), $pdo, new MySqlAggregateStreamStrategy());
    $eventEmitter = new ProophActionEventEmitter();
    $eventStore = new ActionEventEmitterEventStore($eventStore, $eventEmitter);

    $eventBus = new EventBus($eventEmitter);
    $eventPublisher = new EventPublisher($eventBus);
    $eventPublisher->attachToEventStore($eventStore);

    $pdoSnapshotStore = new PdoSnapshotStore($pdo);
    $userRepository = new UserRepository($eventStore, $pdoSnapshotStore);

    $projectionManager = new MySqlProjectionManager($eventStore, $pdo);

    $commandBus = new CommandBus();
    $router = new CommandRouter();
    $router->route(RegisterUser::class)->to(new RegisterUserHandler($userRepository));
    $router->route(ChangeEmail::class)->to(new ChangeEmailHandler($userRepository));
    $router->attachToMessageBus($commandBus);

    $userProjector = new UserProjector($pdo);
    $eventRouter = new EventRouter();
    $eventRouter->route(EmailChanged::class)->to([$userProjector, 'onEmailChanged']);
    $eventRouter->route(UserRegistered::class)->to([$userProjector, 'onUserRegistered']);
    $eventRouter->attachToMessageBus($eventBus);

    $userId = '20';
}
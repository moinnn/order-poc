<?php

namespace {

    use App\Domain\Entity\User;
    use Prooph\Common\Messaging\Message;
    use Prooph\EventStore\Projection\Projector;

    require dirname(dirname(__FILE__)) . '/config/config.php';

    $projection = $projectionManager->createProjection('test', [Projector::OPTION_PCNTL_DISPATCH => true,]);
    $projection->reset();

    pcntl_signal(SIGQUIT, function () use ($projection) {
        $projection->stop();
    });
    $projection
        ->fromCategory(User::class)
        ->whenAny(
            function (array $state, Message $event) use ($eventBus): array {
                $eventBus->dispatch($event);
                return $state;
            }
        );
    $projection->run(false);
}
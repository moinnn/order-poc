<?php
namespace {
    use App\Domain\Service\Command\ChangeEmail;
    use App\Domain\Service\Command\RegisterUser;

    require dirname(dirname(__FILE__)) . '/config/config.php';

    $commandBus->dispatch(new RegisterUser([
           'id' => $userId,
           'email' => 'random@email.com',
           'password' => 'test'
    ]));

    for ($i = 0; $i < 5; $i++) {
        $commandBus->dispatch(new ChangeEmail([
              'email' => 'random' . $i . '@email.com',
              'id' => $userId
        ]));
    }

}
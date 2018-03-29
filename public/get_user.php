<?php
namespace {
    require dirname(dirname(__FILE__)) . '/config/config.php';

    var_dump($userRepository->get($userId));
}
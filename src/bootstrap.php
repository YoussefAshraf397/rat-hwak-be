<?php

namespace App;

use Symfony\Component\Dotenv\Dotenv;

$dotenv = new Dotenv(true);
$dotenv
    ->usePutenv()
    ->bootEnv(dirname(__DIR__) . '/.env');


class bootstrap
{
}

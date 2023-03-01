<?php

namespace App\Tests;

use Symfony\Component\Dotenv\Dotenv;

require \dirname(__DIR__).'/vendor/autoload.php';

if (\file_exists(\dirname(__DIR__).'/config/bootstrap.php')) {
    require \dirname(__DIR__).'/config/bootstrap.php';
} elseif (\method_exists(\Symfony\Component\Dotenv\Dotenv::class, 'bootEnv')) {
    (new Dotenv())->bootEnv(\dirname(__DIR__).'/.env');
}

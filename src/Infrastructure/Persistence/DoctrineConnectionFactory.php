<?php

namespace App\Infrastructure\Persistence;

use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Connection;

class DoctrineConnectionFactory
{
    public static function create(): Connection
    {
        $connectionParams = [
            'dbname' => 'cow_app',
            'user' => 'user',
            'password' => 'password',
            'host' => 'mysql',
            'driver' => 'pdo_mysql',
        ];

        return DriverManager::getConnection($connectionParams);
    }
}

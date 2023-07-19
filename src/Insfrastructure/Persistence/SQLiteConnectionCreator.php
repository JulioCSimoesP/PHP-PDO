<?php

namespace juliocsimoesp\PhpPdo\Insfrastructure\Persistence;

use PDO;

class SQLiteConnectionCreator
{
    const PATH = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'bd.sqlite';

    public static function createConnection(): PDO
    {
        $pdo = new PDO('sqlite:' . self::PATH);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        return $pdo;
    }
}
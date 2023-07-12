<?php

const PATH = __DIR__ . DIRECTORY_SEPARATOR . 'bd.sqlite';

if (file_exists(PATH)) {
    $pdo = new PDO('sqlite:' . PATH);
    echo 'Conectado' . PHP_EOL;

    try {

        $pdo->exec('CREATE TABLE students (
            id INTEGER PRIMARY KEY,
            name TEXT,
            birth_date TEXT
    );');
        echo 'Tabela "students criada' . PHP_EOL;

    } catch (PDOException $e) {

        echo 'Tabela "students já foi criada.' . PHP_EOL;

    }

} else {

    echo 'Não foi possível conectar' . PHP_EOL;

}

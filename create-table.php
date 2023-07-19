<?php

use juliocsimoesp\PhpPdo\Insfrastructure\Persistence\SQLiteConnectionCreator;

require_once 'vendor/autoload.php';

$pdo = SQLiteConnectionCreator::createConnection();
$createTableQuery1 = "
CREATE TABLE IF NOT EXISTS students (
    id INTEGER PRIMARY KEY,
    name TEXT,
    birth_date TEXT
);";
$createTableQuery2 = "
CREATE TABLE IF NOT EXISTS phone (
    id INTEGER PRIMARY KEY,
    area_code TEXT,
    number TEXT,
    student_id INTEGER,
    FOREIGN KEY (student_id) REFERENCES students(id)
);";
$statement = $pdo->prepare($createTableQuery1);
$statement->execute();
$statement = $pdo->prepare($createTableQuery2);
$statement->execute();
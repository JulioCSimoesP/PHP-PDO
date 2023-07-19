<?php

use juliocsimoesp\PhpPdo\Domain\Model\Student;
use juliocsimoesp\PhpPdo\Insfrastructure\Persistence\SQLiteConnectionCreator;
use juliocsimoesp\PhpPdo\Insfrastructure\Repository\PdoStudentRepository;

require_once 'vendor/autoload.php';

$pdo = SQLiteConnectionCreator::createConnection();
$repository = new PdoStudentRepository($pdo);

try {

    //Os seguintes alunos não serão inseridos
    $pdo->beginTransaction();
    $repository->insert(new Student(null, 'Marina Soares', DateTimeImmutable::createFromFormat('d-m-Y', '19-08-2005')));
    $repository->insert(new Student(null, 'Sabrina Sato', DateTimeImmutable::createFromFormat('d-m-Y', '04-03-1978')));
    $pdo->rollBack();

    //Os seguintes alunos serão inseridos
    $pdo->beginTransaction();
    $repository->insert(new Student(null, 'Carlos Peres', DateTimeImmutable::createFromFormat('d-m-Y', '10-08-2000')));
    $repository->insert(new Student(null, 'Patrícia Arboredo', DateTimeImmutable::createFromFormat('d-m-Y', '24-03-1951')));
    $pdo->commit();

} catch (PDOException $exception) {
    echo $exception->getMessage() . PHP_EOL;
    echo $exception->errorInfo[2] . PHP_EOL;
    $pdo->rollBack();
}
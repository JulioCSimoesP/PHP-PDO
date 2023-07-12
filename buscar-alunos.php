<?php

require_once 'vendor/autoload.php';

use juliocsimoesp\PhpPdo\Domain\Model\Student;

const PATH = __DIR__ . DIRECTORY_SEPARATOR . 'bd.sqlite';
$pdo = new PDO('sqlite:' . PATH);

function mainUI(): void
{
    echo <<<FIM

--------------------------------------------------

Menu:
1 - Buscar alunos
2 - Sair

--------------------------------------------------

FIM;
}

function searchOptionsUI(): void
{
    echo <<<FIM

--------------------------------------------------

Menu:
1 - Listar todos os alunos - método 1
2 - Listar todos os alunos - método 2
3 - Listar dados de uma coluna
4 - Buscar aluno específico
5 - Voltar

--------------------------------------------------

FIM;
}

function searchOptions(): void
{
    $continue = true;

    while ($continue) {
        searchOptionsUI();
        $option = trim(fgets(STDIN));
        match ($option) {
            '1' => selectAll(),
            '2' => selectOneByOne(),
            '3' => selectColumn(),
            '4' => selectSingle(),
            '5' => finish($continue),
            default => invalid()
        };
    }
}

function selectAll(): void
{
    global $pdo;

    $readQuery = 'SELECT * FROM students';
    $queryResult = $pdo->query($readQuery)->fetchAll(PDO::FETCH_ASSOC);

    foreach ($queryResult as $item) {
        $student = new Student(null, $item['name'], DateTimeImmutable::createFromFormat('d-m-Y', $item['birth_date']));
        echo $student;
    }
}

function selectOneByOne(): void
{
    global $pdo;

    $readQuery = 'SELECT * FROM students';
    $queryResult = $pdo->query($readQuery);

    while ($resultFetch = $queryResult->fetch(PDO::FETCH_ASSOC)) {
        $student = new Student(null, $resultFetch['name'], DateTimeImmutable::createFromFormat('d-m-Y', $resultFetch['birth_date']));
        echo $student;
    }
}

function selectColumn(): void
{
    global $pdo;

    $readQuery = 'SELECT * FROM students';
    $queryResult = $pdo->query($readQuery);

    echo 'Digite o número da coluna que você deseja buscar os dados:' . PHP_EOL;
    $option = (int)trim(fgets(STDIN));

    while ($resultFetch = $queryResult->fetchColumn($option)) {
        echo $resultFetch . PHP_EOL;
    }
}

function selectSingle(): void
{
    global $pdo;

    echo 'Digite o nome do aluno que você deseja buscar os dados:' . PHP_EOL;
    $option = trim(fgets(STDIN));

    $readQuery = "SELECT * FROM students WHERE name = '$option';";
    $queryResult = $pdo->query($readQuery)->fetch(PDO::FETCH_ASSOC);
    $student = new Student(null, $queryResult['name'], DateTimeImmutable::createFromFormat('d-m-Y', $queryResult['birth_date']));
    echo $student;
}

function finish(bool &$control): void
{
    $control = false;
}

function invalid(): void
{
    echo 'Opção inválida' . PHP_EOL;
}

function main(): void
{
    $continue = true;

    while ($continue) {
        mainUI();
        $option = trim(fgets(STDIN));
        match ($option) {
            '1' => searchOptions(),
            '2' => finish($continue),
            default => invalid()
        };
    }
}

main();
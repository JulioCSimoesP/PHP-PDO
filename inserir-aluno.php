<?php

require_once 'vendor/autoload.php';

use juliocsimoesp\PhpPdo\Domain\Model\Student;

const PATH = __DIR__ . DIRECTORY_SEPARATOR . 'bd.sqlite';
$pdo = new PDO('sqlite:' . PATH);

function UI(): void
{
    echo <<<FIM

--------------------------------------------------

Menu:
1 - Inserir aluno
2 - Sair

--------------------------------------------------

FIM;
}

function insert(): void
{
    echo 'Digite o nome do aluno que você deseja inserir:' . PHP_EOL;
    $name = trim(fgets(STDIN));
    if (nameExists($name)) {
        return;
    }
    echo 'Digite o ano de nascimento do aluno:' . PHP_EOL;
    $Y = trim(fgets(STDIN));
    echo 'Digite o mês de nascimento do aluno:' . PHP_EOL;
    $m = trim(fgets(STDIN));
    echo 'Digite o dia de nascimento do aluno:' . PHP_EOL;
    $d = trim(fgets(STDIN));

    $student = new Student(null, $name, new DateTimeImmutable("$Y-$m-$d"));

    sqlInsert($student);
}

function nameExists(string $name): bool
{
    global $pdo;

    $verifyNameQuery = "SELECT * FROM students WHERE name = ?;";
    $prepareStatement = $pdo->prepare($verifyNameQuery);
    $prepareStatement->bindValue(1, $name, PDO::PARAM_STR);
    $prepareStatement->execute();

    if ($prepareStatement->fetchAll()) {
        echo 'Já existe um aluno com esse nome.' . PHP_EOL;
        return true;
    }

    return false;
}

function sqlInsert(Student $student): void
{
    global $pdo;

    $insertQuery = "INSERT INTO students (name, birth_date) VALUES (:name, :birth_date);";
    $prepareStatement = $pdo->prepare($insertQuery);
    $prepareStatement->bindValue(':name', $student->name());
    $prepareStatement->bindValue(':birth_date', $student->birthDate()->format('d-m-Y'));
    $prepareStatement->execute();

    echo 'Aluno inserido com sucesso' . PHP_EOL;
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
        UI();
        $option = trim(fgets(STDIN));

        match ($option) {
            '1' => insert(),
            '2' => finish($continue),
            default => invalid()
        };
    }

    echo 'Finalizando programa' . PHP_EOL;
}

main();

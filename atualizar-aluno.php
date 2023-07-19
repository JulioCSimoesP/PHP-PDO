<?php

require_once 'vendor/autoload.php';

use juliocsimoesp\PhpPdo\Domain\Model\Student;
use juliocsimoesp\PhpPdo\Insfrastructure\Persistence\SQLiteConnectionCreator;

$pdo = SQLiteConnectionCreator::createConnection();

function UI(): void
{
    echo <<<FIM

--------------------------------------------------

Menu:
1 - Atualizar aluno
2 - Sair

--------------------------------------------------

FIM;
}

function update(): void
{
    echo 'Digite o id do aluno que você deseja atualizar os dados:' . PHP_EOL;
    $id = (int)trim(fgets(STDIN));
    if (idNotExist($id)) {
        return;
    }
    echo 'Digite o nome do aluno:' . PHP_EOL;
    $name = trim(fgets(STDIN));
    echo 'Digite o ano de nascimento do aluno:' . PHP_EOL;
    $Y = trim(fgets(STDIN));
    echo 'Digite o mês de nascimento do aluno:' . PHP_EOL;
    $m = trim(fgets(STDIN));
    echo 'Digite o dia de nascimento do aluno:' . PHP_EOL;
    $d = trim(fgets(STDIN));

    $student = new Student($id, $name, new DateTimeImmutable("$Y-$m-$d"));

    sqlUpdate($student);
}

function idNotExist(int $id): bool
{
    global $pdo;

    $verifyQuery = "SELECT * FROM students WHERE id = ?;";
    $statement = $pdo->prepare($verifyQuery);
    $statement->bindValue(1, $id, PDO::PARAM_INT);
    $statement->execute();

    if (!$statement->fetchAll()) {
        echo 'Não existe um aluno com esse id.' . PHP_EOL;
        return true;
    }

    return false;
}

function sqlUpdate(Student $student): void
{
    global $pdo;

    $insertQuery = "UPDATE students SET name = :name, birth_date = :birth_date WHERE id = :id;";
    $prepareStatement = $pdo->prepare($insertQuery);
    $prepareStatement->bindValue(':name', $student->name());
    $prepareStatement->bindValue(':birth_date', $student->birthDate()->format('d-m-Y'));
    $prepareStatement->bindValue(':id', $student->id());
    $prepareStatement->execute();

    echo 'Aluno atualizado com sucesso' . PHP_EOL;
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
            '1' => update(),
            '2' => finish($continue),
            default => invalid()
        };
    }

    echo 'Finalizando programa' . PHP_EOL;
}

main();

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
1 - Deletar aluno
2 - Sair

--------------------------------------------------

FIM;
}

function remove(): void
{
    global $pdo;

    echo 'Digite o ID do aluno que você deseja deletar: ' . PHP_EOL;
    $id = (int)trim(fgets(STDIN));

    if (invalidEntry($id)) {
        echo 'Não existe um aluno com o ID informado' . PHP_EOL;
        return;
    }

    $deleteQuery = "DELETE FROM students WHERE id = ?;";
    $prepareStatement = $pdo->prepare($deleteQuery);
    $prepareStatement->bindValue(1, $id, PDO::PARAM_INT);
    $prepareStatement->execute();

    echo 'Aluno deletado com sucesso' . PHP_EOL;
}

function invalidEntry(int $id): bool
{
    global $pdo;

    $readQuery = 'SELECT * FROM students WHERE id = ?;';
    $prepareStatement = $pdo->prepare($readQuery);
    $prepareStatement->bindParam(1, $id, PDO::PARAM_INT);
    $prepareStatement->execute();

    if (!$prepareStatement->fetch(PDO::FETCH_ASSOC)) {
        return true;
    }

    return false;
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
            '1' => remove(),
            '2' => finish($continue),
            default => invalid()
        };
    }

    echo 'Finalizando programa' . PHP_EOL;
}

main();
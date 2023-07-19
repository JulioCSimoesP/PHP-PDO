<?php

namespace juliocsimoesp\PhpPdo\Insfrastructure\Repository;

use DateTimeImmutable;
use DateTimeInterface;
use juliocsimoesp\PhpPdo\Domain\Model\Phone;
use juliocsimoesp\PhpPdo\Domain\Model\Student;
use juliocsimoesp\PhpPdo\Domain\Repository\StudentRepository;
use PDO;
use PDOStatement;

class PdoStudentRepository implements StudentRepository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function allStudents(): array
    {
        $readQuery = "SELECT * FROM students";
        $statement = $this->pdo->prepare($readQuery);
        $statement->execute();

        return $this->hydrateStudentList($statement);
    }

    public function studentsWithPhones(): array
    {
        $readQuery = "
            SELECT students.id, 
                   students.name, 
                   students.birth_date, 
                   phone.id AS phone_id, 
                   phone.area_code, 
                   phone.number 
            FROM students JOIN phone ON students.id = phone.student_id;
        ";
        $statement = $this->pdo->prepare($readQuery);
        $statement->execute();

        return $this->hydrateStudentsWithPhones($statement);
    }

    private function hydrateStudentsWithPhones(PDOStatement $statement): array
    {
        $queryResult = $statement->fetchAll();
        $studentList = [];

        foreach ($queryResult as $result) {
            if (!array_key_exists($result['id'], $studentList)) {
                $studentList[$result['id']] = new Student(
                    $result['id'],
                    $result['name'],
                    DateTimeImmutable::createFromFormat('d-m-Y', $result['birth_date'])
                );
            }

            $studentList[$result['id']]->addPhone(new Phone(
                $result['phone_id'],
                $result['area_code'],
                $result['number']
            ));
        }

        return $studentList;
    }

    public function studensBirthAt(DateTimeInterface $birthDate): array
    {
        $readQuery = "SELECT * FROM students WHERE birth_date = ?";
        $statement = $this->pdo->prepare($readQuery);
        $statement->execute([$birthDate->format('d-m-Y')]);

        return $this->hydrateStudentList($statement);
    }

    public function studentById(int $id): array
    {
        $readQuery = "SELECT * FROM students WHERE id = ?";
        $statement = $this->pdo->prepare($readQuery);
        $statement->execute([$id]);

        return $this->hydrateStudentList($statement);
    }

    private function hydrateStudentList(PDOStatement $statement): array
    {
        $queryResult = $statement->fetchAll(PDO::FETCH_ASSOC);
        $studentList = [];

        foreach ($queryResult as $data) {
            $studentList[] = new Student(
                $data['id'],
                $data['name'],
                DateTimeImmutable::createFromFormat('d-m-Y', $data['birth_date'])
            );
        }

        return $studentList;
    }

    public function insert(Student $student): bool
    {
        $insertQuery = "INSERT INTO students (name, birth_date) VALUES (:name, :birth_date);";
        $statement = $this->pdo->prepare($insertQuery);
        $statement->bindValue(':name', $student->name(), PDO::PARAM_STR);
        $statement->bindValue(':birth_date', $student->birthDate()->format('d-m-Y'), PDO::PARAM_STR);
        $result = $statement->execute();

        $student->defineId($this->pdo->lastInsertId());

        return $result;
    }

    public function update(Student $student): bool
    {
        $updateQuery = "UPDATE students SET name = :name, birth_date = :birth_date WHERE id = :id;";
        $statement = $this->pdo->prepare($updateQuery);
        $statement->bindValue(':name', $student->name(), PDO::PARAM_STR);
        $statement->bindValue(':birth_date', $student->birthDate()->format('d-m-Y'), PDO::PARAM_STR);
        $statement->bindValue(':id', $student->id(), PDO::PARAM_INT);

        return $statement->execute();
    }

    public function remove(Student $student): bool
    {
        $deleteQuery = "DELETE FROM students WHERE id = ?;";
        $statement = $this->pdo->prepare($deleteQuery);
        $statement->bindValue(1, $student->id(), PDO::PARAM_INT);

        return $statement->execute();
    }
}

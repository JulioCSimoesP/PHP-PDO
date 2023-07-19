<?php

namespace juliocsimoesp\PhpPdo\Domain\Repository;

use DateTimeInterface;
use juliocsimoesp\PhpPdo\Domain\Model\Student;
use PDOStatement;

interface StudentRepository
{
    public function allStudents(): array;

    public function studentsWithPhones(): array;

    public function studensBirthAt(DateTimeInterface $birthDate): array;

    public function studentById(int $id): array;

    public function insert(Student $student): bool;

    public function update(Student $student): bool;

    public function remove(Student $student): bool;
}
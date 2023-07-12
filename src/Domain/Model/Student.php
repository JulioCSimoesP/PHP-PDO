<?php

namespace juliocsimoesp\PhpPdo\Domain\Model;

use DateTimeImmutable;
use DateTimeInterface;

class Student
{
    /**
     * @var int|null
     * @property-read int $id
     * @property-read string $name
     */
    private ?int $id;
    private string $name;
    private DateTimeInterface $birthDate;

    public function __construct(?int $id, string $name, DateTimeInterface $birthDate)
    {
        $this->id = $id;
        $this->name = $name;
        $this->birthDate = $birthDate;
    }

    public function __toString(): string
    {
        $data = 'Aluno: ' . $this->name . '; Nascimento: ' . $this->birthDate->format('d-m-Y') . '; Idade: ' . $this->age() . PHP_EOL;
        return $data;
    }

    public function id(): ?int
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function birthDate(): DateTimeInterface
    {
        return $this->birthDate;
    }

    public function age(): int
    {
        return $this->birthDate
            ->diff(new DateTimeImmutable())
            ->y;
    }
}

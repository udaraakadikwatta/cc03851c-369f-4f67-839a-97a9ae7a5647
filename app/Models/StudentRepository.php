<?php
namespace App\Models;

/**
 * Class StudentRepository
 */
class StudentRepository
{
    private array $students;

    public function __construct()
    {
        $this->students = JsonLoader::load('students.json');
    }

    /**
     * Retrieve all students.
     *
     * @return array
     */
    public function all(): array
    {
        return $this->students;
    }

    /**
     * Find a specific student response by its student id.
     *
     * @param string $id
     * @return array|null
     */
    public function find(string $id): ?array
    {
        foreach ($this->students as $student) {
            if ($student['id'] === $id) return $student;
        }
        return null;
    }
}

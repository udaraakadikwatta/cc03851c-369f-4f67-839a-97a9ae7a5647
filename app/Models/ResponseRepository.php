<?php
namespace App\Models;

/**
 * Class ResponseRepository
 */
class ResponseRepository
{
    private array $responses;

    public function __construct()
    {
        $this->responses = JsonLoader::load('student-responses.json');
    }

    /**
     * Retrieve all responses.
     *
     * @return array
     */
    public function all(): array
    {
        return $this->responses;
    }

    /**
     * Find a specific response by its student id.
     *
     * @param string $id
     * @return array|null
     */
    public function byStudent(string $studentId): array
    {
        return array_filter($this->responses, fn($r) =>
            isset($r['student']['id']) && $r['student']['id'] === $studentId
        );
    }
}

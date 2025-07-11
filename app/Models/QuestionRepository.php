<?php
namespace App\Models;

/**
 * Class QuestionRepository
 */
class QuestionRepository
{
    private array $questions;

    public function __construct()
    {
        $this->questions = JsonLoader::load('questions.json');
    }

    /**
     * Retrieve all questions.
     *
     * @return array
     */
    public function all(): array
    {
        return $this->questions;
    }

    /**
     * Find a specific question by its id.
     *
     * @param string $id
     * @return array|null
     */
    public function find(string $id): ?array
    {
        foreach ($this->questions as $question) {
            if ($question['id'] === $id) return $question;
        }
        return null;
    }
}

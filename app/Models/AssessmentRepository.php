<?php
namespace App\Models;

/**
 * Class AssessmentRepository
 */
class AssessmentRepository
{
    /**
     * @var array
     */
    private array $assessments;

    public function __construct()
    {
        $this->assessments = JsonLoader::load('assessments.json');
    }

    /**
     * Retrieve all assessments.
     *
     * @return array
     */
    public function all(): array
    {
        return $this->assessments;
    }

    /**
     * Find a specific assessment by its ID.
     *
     * @param string $id
     * @return array|null
     */
    public function find(string $id): ?array
    {
        foreach ($this->assessments as $assessment) {
            if ($assessment['id'] === $id) return $assessment;
        }
        return null;
    }
}

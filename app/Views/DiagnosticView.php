<?php
namespace App\Views;

use App\Models\StudentRepository;
use App\Models\AssessmentRepository;
use App\Models\QuestionRepository;
use App\Models\ResponseRepository;

/**
 * Class DiagnosticView
 * 
 * Generates a diagnostic report for a given student.
 */
class DiagnosticView
{
    private StudentRepository $students;
    private AssessmentRepository $assessments;
    private QuestionRepository $questions;
    private ResponseRepository $responses;

    /**
     * Constructor
     *
     * @param StudentRepository $students
     * @param AssessmentRepository $assessments
     * @param QuestionRepository $questions
     * @param ResponseRepository $responses
     */
    public function __construct(
        StudentRepository $students,
        AssessmentRepository $assessments,
        QuestionRepository $questions,
        ResponseRepository $responses
    ) {
        $this->students = $students;
        $this->assessments = $assessments;
        $this->questions = $questions;
        $this->responses = $responses;
    }

    /**
     * Renders the diagnostic report for the given student.
     *
     * @param string $studentId
     * @return void
     */
    public function render(string $studentId): void
    {
        $student = $this->students->find($studentId);
        if (!$student) {
            echo "Student ID '$studentId' not found.\n";
            return;
        }

        $studentResponses = array_filter(
            $this->responses->all(),
            fn($r) => isset($r['student']['id']) && $r['student']['id'] === $studentId && !empty($r['completed'])
        );

        if (empty($studentResponses)) {
            echo "No completed assessments found for {$student['firstName']} {$student['lastName']}.\n";
            return;
        }

        usort($studentResponses, fn($a, $b) => strtotime($b['completed']) <=> strtotime($a['completed']));
        $latest = $studentResponses[0];

        $assessment = $this->assessments->find($latest['assessmentId']);
        $date = date('jS F Y h:i A', strtotime($latest['completed']));

        $totalCorrect = 0;
        $strandCounts = [];
        $strandCorrect = [];

        foreach ($latest['responses'] as $response) {
            $question = $this->questions->find($response['questionId']);
            if (!$question) continue;

            $strand = $question['strand'];
            $correctAnswer = $question['config']['key'];

            $strandCounts[$strand] = ($strandCounts[$strand] ?? 0) + 1;

            if ($response['response'] === $correctAnswer) {
                $strandCorrect[$strand] = ($strandCorrect[$strand] ?? 0) + 1;
                $totalCorrect++;
            }
        }

        echo "{$student['firstName']} {$student['lastName']} recently completed {$assessment['name']} assessment on {$date}\n";
        echo "He got {$totalCorrect} questions right out of " . count($latest['responses']) . ". Details by strand given below:\n\n";

        foreach ($strandCounts as $strand => $total) {
            $correct = $strandCorrect[$strand] ?? 0;
            echo "{$strand}: {$correct} out of {$total} correct\n";
        }
    }
}

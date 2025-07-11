<?php
namespace App\Views;

use App\Models\StudentRepository;
use App\Models\AssessmentRepository;
use App\Models\QuestionRepository;
use App\Models\ResponseRepository;
use DateTime;

/**
 * Class ProgressView
 * 
 * Displays the progress of a student.
 */
class ProgressView
{
    private StudentRepository $students;
    private AssessmentRepository $assessments;
    private QuestionRepository $questions;
    private ResponseRepository $responses;

    /**
     * Constructor to inject repositories
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
     * Render the progress report for a given student
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

        if (count($studentResponses) < 2) {
            echo "Not enough completed assessments to show progress for {$student['firstName']} {$student['lastName']}.\n";
            return;
        }

        $grouped = [];
        foreach ($studentResponses as $response) {
            $grouped[$response['assessmentId']][] = $response;
        }

        foreach ($grouped as $assessmentId => $attempts) {
            $assessment = $this->assessments->find($assessmentId);
            $assessmentName = $assessment['name'] ?? 'Unknown';

            usort($attempts, fn($a, $b) => strtotime($a['completed']) <=> strtotime($b['completed']));

            $totalQuestions = count($attempts[0]['responses']);
            $scores = [];

            echo "{$student['firstName']} {$student['lastName']} has completed {$assessmentName} assessment " . count($attempts) . " times in total. Date and raw score given below:\n\n";

            foreach ($attempts as $attempt) {
                $date = (DateTime::createFromFormat("d/m/Y H:i:s", $attempt['completed']))->format("jS F Y");
                
                $correct = 0;

                foreach ($attempt['responses'] as $r) {
                    $q = $this->questions->find($r['questionId']);
                    if ($q && $r['response'] === $q['config']['key']) {
                        $correct++;
                    }
                }

                $scores[] = $correct;
                echo "Date: {$date},  Raw Score: {$correct} out of {$totalQuestions}\n";
            }

            $difference = end($scores) - $scores[0];
            $change = $difference > 0
                ? "got {$difference} more correct"
                : ($difference < 0
                    ? "got " . abs($difference) . " fewer correct"
                    : "got the same score");

            echo "\n{$student['firstName']} {$student['lastName']} {$change} in the recent completed assessment than the oldest.\n\n";
        }
    }
}

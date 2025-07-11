<?php
namespace App\Views;

use App\Models\StudentRepository;
use App\Models\AssessmentRepository;
use App\Models\QuestionRepository;
use App\Models\ResponseRepository;

/**
 * Class FeedbackView
 * 
 * Generates a feedback report for a given student.
 */
class FeedbackView
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
     * Renders the feedback report for the given student
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
            fn($res) => isset($res['student']['id']) && $res['student']['id'] === $studentId && !empty($res['completed'])
        );

        if (empty($studentResponses)) {
            echo "No completed assessments found for {$student['firstName']} {$student['lastName']}.\n";
            return;
        }

        usort($studentResponses, fn($a, $b) => strtotime($b['completed']) <=> strtotime($a['completed']));
        $latest = $studentResponses[0];

        $assessment = $this->assessments->find($latest['assessmentId']);
        $date = date('jS F Y h:i A', strtotime($latest['completed']));

        $correctCount = 0;
        $totalQuestions = count($latest['responses']);
        $wrongAnswers = [];

        foreach ($latest['responses'] as $response) {
            $question = $this->questions->find($response['questionId']);
            if (!$question) continue;

            $correctKey = $question['config']['key'];
            $studentAnswer = $response['response'];

            if ($studentAnswer === $correctKey) {
                $correctCount++;
            } else {
                $studentOption = array_filter(
                    $question['config']['options'],
                    fn($opt) => $opt['id'] === $studentAnswer
                );
                $studentOption = array_values($studentOption)[0] ?? ['label' => 'Unknown', 'value' => 'Unknown'];

                $correctOption = array_filter(
                    $question['config']['options'],
                    fn($opt) => $opt['id'] === $correctKey
                );
                $correctOption = array_values($correctOption)[0] ?? ['label' => 'Unknown', 'value' => 'Unknown'];

                $wrongAnswers[] = [
                    'stem' => $question['stem'] ?? '',
                    'yourAnswerLabel' => $studentOption['label'],
                    'yourAnswerValue' => $studentOption['value'],
                    'correctAnswerLabel' => $correctOption['label'],
                    'correctAnswerValue' => $correctOption['value'],
                    'hint' => $question['config']['hint'] ?? 'No hint available.',
                ];
            }
        }

        echo "{$student['firstName']} {$student['lastName']} recently completed {$assessment['name']} assessment on {$date}\n";
        echo "He got {$correctCount} questions right out of {$totalQuestions}. Feedback for wrong answers given below\n\n";

        foreach ($wrongAnswers as $wrong) {
            echo "Question: {$wrong['stem']}\n";
            echo "Your answer: {$wrong['yourAnswerLabel']} with value {$wrong['yourAnswerValue']}\n";
            echo "Right answer: {$wrong['correctAnswerLabel']} with value {$wrong['correctAnswerValue']}\n";
            echo "Hint: {$wrong['hint']}\n\n";
        }
    }
}

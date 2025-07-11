<?php
namespace App\Controllers;

use App\Models\AssessmentRepository;
use App\Models\QuestionRepository;
use App\Models\ResponseRepository;
use App\Models\StudentRepository;
use App\Views\DiagnosticView;
use App\Views\FeedbackView;
use App\Views\ProgressView;

/**
 * Controller class that handles report.
 */
class ReportController
{
    private StudentRepository $studentRepo;
    private AssessmentRepository $assessmentRepo;
    private QuestionRepository $questionRepo;
    private ResponseRepository $responseRepo;

    public function __construct()
    {
        $this->studentRepo = new StudentRepository();
        $this->assessmentRepo = new AssessmentRepository();
        $this->questionRepo = new QuestionRepository();
        $this->responseRepo = new ResponseRepository();
    }

    /**
     * Main method to run the CLI-based report generator
     */
    public function run(): void
    {
        echo "Please enter Student ID: ";
        $studentId = trim(fgets(STDIN));

        echo "Report to generate (1 for Diagnostic, 2 for Progress, 3 for Feedback): ";
        $reportType = trim(fgets(STDIN));

        switch ($reportType) {
            case '1':
                (new DiagnosticView(
                    $this->studentRepo,
                    $this->assessmentRepo,
                    $this->questionRepo,
                    $this->responseRepo
                ))->render($studentId);
                break;

            case '2':
                (new ProgressView(
                    $this->studentRepo,
                    $this->assessmentRepo,
                    $this->questionRepo,
                    $this->responseRepo
                ))->render($studentId);
                break;

            case '3':
                (new FeedbackView(
                    $this->studentRepo,
                    $this->assessmentRepo,
                    $this->questionRepo,
                    $this->responseRepo
                ))->render($studentId);
                break;

            default:
                echo "Invalid report type selected.\n";
        }
    }
}

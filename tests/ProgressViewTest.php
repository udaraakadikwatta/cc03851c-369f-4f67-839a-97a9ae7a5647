<?php
namespace Tests;

use App\Views\ProgressView;
use App\Models\StudentRepository;
use App\Models\AssessmentRepository;
use App\Models\QuestionRepository;
use App\Models\ResponseRepository;
use PHPUnit\Framework\TestCase;

/**
 * Class ProgressViewTest
 */
class ProgressViewTest extends TestCase
{
    /**
     * Test that the ProgressView generates output containing expected progress information.
     */
    public function testProgressReportOutput(): void
    {
        $studentRepo = new StudentRepository();
        $assessmentRepo = new AssessmentRepository();
        $questionRepo = new QuestionRepository();
        $responseRepo = new ResponseRepository();

        ob_start();
        $view = new ProgressView($studentRepo, $assessmentRepo, $questionRepo, $responseRepo);
        $view->render('student3');
        $output = ob_get_clean();

        $this->assertStringContainsString('has completed', $output);
        $this->assertStringContainsString('Raw Score', $output);
        $this->assertStringContainsString('got', $output);
    }
}

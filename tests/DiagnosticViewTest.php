<?php
namespace Tests;

use App\Views\DiagnosticView;
use App\Models\StudentRepository;
use App\Models\AssessmentRepository;
use App\Models\QuestionRepository;
use App\Models\ResponseRepository;
use PHPUnit\Framework\TestCase;

/**
 * Class DiagnosticViewTest
 */
class DiagnosticViewTest extends TestCase
{
    /**
     * Test that the DiagnosticView generates output containing expected diagnostic information.
     */
    public function testDiagnosticReportOutput(): void
    {
        $studentRepo = new StudentRepository();
        $assessmentRepo = new AssessmentRepository();
        $questionRepo = new QuestionRepository();
        $responseRepo = new ResponseRepository();

        ob_start();
        $view = new DiagnosticView($studentRepo, $assessmentRepo, $questionRepo, $responseRepo);
        $view->render('student3');
        $output = ob_get_clean();

        $this->assertStringContainsString('recently completed', $output);
        $this->assertStringContainsString('Number and Algebra', $output);
        $this->assertStringContainsString('correct', $output);
    }
}

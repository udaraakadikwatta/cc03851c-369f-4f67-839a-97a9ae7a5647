<?php
namespace Tests;

use App\Views\FeedbackView;
use App\Models\StudentRepository;
use App\Models\AssessmentRepository;
use App\Models\QuestionRepository;
use App\Models\ResponseRepository;
use PHPUnit\Framework\TestCase;

/**
 * Class FeedbackViewTest
 */
class FeedbackViewTest extends TestCase
{
    /**
     * Test that the FeedbackView generates output containing expected feedback information.
     */
    public function testFeedbackReportOutput(): void
    {
        $studentRepo = new StudentRepository();
        $assessmentRepo = new AssessmentRepository();
        $questionRepo = new QuestionRepository();
        $responseRepo = new ResponseRepository();

        ob_start();
        $view = new FeedbackView($studentRepo, $assessmentRepo, $questionRepo, $responseRepo);
        $view->render('student3');
        $output = ob_get_clean();

        $this->assertStringContainsString('Feedback for wrong answers', $output);
        $this->assertStringContainsString('Question:', $output);
        $this->assertStringContainsString('Hint:', $output);
    }
}

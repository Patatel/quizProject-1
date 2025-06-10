<?php
namespace QuizProject\Tests;

require_once __DIR__ . '/../api/models/QuizModel.php';
require_once __DIR__ . '/../api/models/ResultModel.php';

class ResultModelTest extends TestCase
{
    public function testGetResultsByUser(): void
    {
        $quizModel = new \QuizModel($this->pdo);
        $resultModel = new \ResultModel($this->pdo);
        $quizId = $quizModel->createQuiz('Qz', 'Desc', 1);
        $questions = [
            ['text' => 'q1', 'answers' => ['a1','a2'], 'correctAnswerIndex' => 0]
        ];
        $quizModel->createQuestionsForQuiz($quizId, $questions);
        $quizModel->userSubmitAnswers(1, $quizId, [
            ['question_id'=>1,'user_answer'=>'a1']
        ]);

        $results = $resultModel->getResultsByUser(1);
        $this->assertCount(1, $results);
        $this->assertEquals('Qz', $results[0]['title']);
    }
}

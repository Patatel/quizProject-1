<?php
namespace QuizProject\Tests;

require_once __DIR__ . '/../api/models/QuizModel.php';
require_once __DIR__ . '/../api/models/ResultModel.php';

class ResultModelTest extends TestCase
{
    public function testGetResultsByUser(): void
    {
        // First create a test user
        $userModel = new \UserModel($this->pdo);
        $userModel->createUser('TestUser', 'test@example.com', 'password');
        $userId = $this->pdo->lastInsertId();
        
        $quizModel = new \QuizModel($this->pdo);
        $resultModel = new \ResultModel($this->pdo);
        $quizId = $quizModel->createQuiz('Qz', 'Desc', $userId);
        $questions = [
            ['text' => 'q1', 'answers' => ['a1','a2'], 'correctAnswerIndex' => 0]
        ];
        $quizModel->createQuestionsForQuiz($quizId, $questions);        // Get the actual question ID
        $questions = $quizModel->getQuizQuestions($quizId);
        $questionId = $questions[0]['id'];
        
        $quizModel->userSubmitAnswers($userId, $quizId, [
            ['question_id' => $questionId, 'user_answer'=>'a1']
        ]);

        $results = $resultModel->getResultsByUser($userId);
        $this->assertCount(1, $results);
        $this->assertEquals('Qz', $results[0]['title']);
    }
}

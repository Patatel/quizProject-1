<?php
namespace QuizProject\Tests;

require_once __DIR__ . '/../api/models/QuizModel.php';

class QuizModelTest extends TestCase
{    public function testCreateAndFetchQuiz(): void
    {
        // Create a test user first
        $userModel = new \UserModel($this->pdo);
        $userModel->createUser('TestUser', 'test@example.com', 'password');
        $userId = $this->pdo->lastInsertId();
        
        $model = new \QuizModel($this->pdo);
        $id = $model->createQuiz('Title', 'Desc', $userId);
        $this->assertIsNumeric($id);

        $quiz = $model->getQuizById($id);
        $this->assertSame('Title', $quiz['title']);
    }    public function testUpdateAndDeleteQuiz(): void
    {
        // Create a test user first
        $userModel = new \UserModel($this->pdo);
        $userModel->createUser('TestUser', 'test@example.com', 'password');
        $userId = $this->pdo->lastInsertId();
        
        $model = new \QuizModel($this->pdo);
        $id = $model->createQuiz('Old', 'Desc', $userId);
        $this->assertTrue($model->updateQuiz($id, 'New', 'Updated'));
        $quiz = $model->getQuizById($id);
        $this->assertSame('New', $quiz['title']);

        $this->assertTrue($model->deleteQuiz($id));
        $this->assertFalse($model->getQuizById($id));
    }    public function testCreateQuestionsAndGetQuizQuestions(): void
    {
        // Create a test user first
        $userModel = new \UserModel($this->pdo);
        $userModel->createUser('TestUser', 'test@example.com', 'password');
        $userId = $this->pdo->lastInsertId();
        
        $model = new \QuizModel($this->pdo);
        $quizId = $model->createQuiz('Qz', 'Desc', $userId);
        $questions = [
            [
                'text' => 'q1',
                'answers' => ['a1', 'a2'],
                'correctAnswerIndex' => 0
            ],
            [
                'text' => 'q2',
                'answers' => ['b1', 'b2'],
                'correctAnswerIndex' => 1
            ]
        ];
        $this->assertTrue($model->createQuestionsForQuiz($quizId, $questions));

        $fetched = $model->getQuizQuestions($quizId);
        $this->assertCount(2, $fetched);
        $this->assertEquals('q1', $fetched[0]['text']);
        $this->assertCount(2, $fetched[0]['answers']);
    }    public function testUserSubmitAnswersCreatesResult(): void
    {
        // Create a test user first
        $userModel = new \UserModel($this->pdo);
        $userModel->createUser('TestUser', 'test@example.com', 'password');
        $userId = $this->pdo->lastInsertId();
        
        $model = new \QuizModel($this->pdo);
        $quizId = $model->createQuiz('Qz', 'Desc', $userId);
        $questions = [
            [
                'text' => 'q1',
                'answers' => ['a1', 'a2'],
                'correctAnswerIndex' => 0
            ]
        ];
        $model->createQuestionsForQuiz($quizId, $questions);

        // Build answers
        $userAnswers = [
            [
                'question_id' => 1,
                'user_answer' => 'a1'
            ]
        ];
        $result = $model->userSubmitAnswers($userId, $quizId, $userAnswers);
        $this->assertTrue($result['success']);
        $this->assertEquals(100.0, $result['percentage']);
    }
}

<?php
/**
 * API Router for QuizPlatform
 * 
 * This file handles all API routes and directs them to the appropriate controllers.
 * Each route is documented with its HTTP method, purpose, and expected behavior.
 */

// Get the HTTP method and route from the request
$method = $_SERVER['REQUEST_METHOD'];
$route = $_GET['route'] ?? '';

/**
 * Authentication Routes
 * -------------------
 * POST /register - New user registration
 * POST /login - User authentication
 */
if ($route === 'register' && $method === 'POST') {
    require_once __DIR__ . '/../api/controllers/AuthController.php';
    register();
} elseif ($route === 'login' && $method === 'POST') {
    require_once __DIR__ . '/../api/controllers/AuthController.php';
    login();
} 

/**
 * Quiz Management Routes
 * --------------------
 * POST   /create_quiz      - Create a new quiz
 * GET    /my_quizzes      - Get quizzes created by the authenticated user
 * GET    /all_quizzes     - Get all available quizzes
 * GET    /get_quiz        - Get a specific quiz by ID
 * DELETE /delete_quiz     - Delete a specific quiz
 * PUT    /update_quiz     - Update quiz details
 */
elseif ($route === 'create_quiz' && $method === 'POST') {
    require_once __DIR__ . '/../api/controllers/QuizController.php';
    createQuiz();
} elseif ($route === 'my_quizzes' && $method === 'GET') {
    require_once __DIR__ . '/../api/controllers/QuizController.php';
    getUserQuizzes();
} elseif ($route === 'all_quizzes' && $method === 'GET') {
    require_once __DIR__ . '/../api/controllers/QuizController.php';
    getQuizzes();
} elseif ($route === 'get_quiz' && $method === 'GET') {
    require_once __DIR__ . '/../api/controllers/QuizController.php';
    getQuizById();
} elseif ($route === 'delete_quiz' && $method === 'GET') {
    http_response_code(405);
    echo json_encode(["error" => "Méthode non autorisée, utilisez DELETE"]);
} elseif ($route === 'delete_quiz' && $method === 'DELETE') {
    require_once __DIR__ . '/../api/controllers/QuizController.php';
    deleteQuiz();
} elseif ($route === 'update_quiz' && $method === 'PUT') {
    require_once __DIR__ . '/../api/controllers/QuizController.php';
    updateQuiz();
}

/**
 * Question Management Routes
 * -----------------------
 * POST /add_questions      - Add questions to a quiz
 * PUT  /update_questions  - Update existing questions
 * GET  /get_questions     - Get questions for a quiz
 * POST /delete_question   - Delete a specific question
 * POST /update_question   - Update a specific question
 * GET  /get_quiz_questions- Get all questions for a quiz
 */
elseif ($route === 'add_questions' && $method === 'POST') {
    require_once __DIR__ . '/../api/controllers/QuizController.php';
    createQuestionsForQuiz();
} elseif ($route === 'update_questions' && $method === 'PUT') {
    require_once __DIR__ . '/../api/controllers/QuizController.php';
    updateQuestions();
} elseif ($route === 'get_questions' && $method === 'GET') {
    require_once __DIR__ . '/../api/controllers/QuizController.php';
    getQuestionsByQuizId();
} elseif ($route === 'delete_question' && $method === 'POST') {
    require_once __DIR__ . '/../api/controllers/QuizController.php';
    deleteQuestion();
} elseif ($route === 'update_question' && $method === 'POST') {
    require_once __DIR__ . '/../api/controllers/QuizController.php';
    updateQuestion();
} elseif ($route === 'get_quiz_questions' && $method === 'GET') {
    require_once __DIR__ . '/../api/controllers/QuizController.php';
    getQuizQuestions();
}

/**
 * User Management Routes
 * -------------------
 * POST /update_user - Update user profile information
 */
elseif ($route === 'update_user' && $method === 'POST') {
    require_once __DIR__ . '/../api/controllers/UserController.php';
    updateUser();
}

/**
 * Answer and Results Routes
 * ----------------------
 * POST /submit_answers      - Submit answers for a quiz
 * GET  /get_answer_details - Get details of submitted answers
 * POST /user_submit_answers- Submit user's quiz answers
 * GET  /get-user-results   - Get user's quiz results
 * GET  /get_user_results   - Get detailed results for a user
 */
elseif ($route === 'submit_answers' && $method === 'POST') {
    require_once __DIR__ . '/../api/controllers/AnswerController.php';
    submitAnswers();
} elseif ($route === 'get_answer_details' && $method === 'GET') {
    require_once __DIR__ . '/../api/controllers/AnswerController.php';
    getAnswerDetails();
} elseif ($route === 'user_submit_answers' && $method === 'POST') {
    require_once __DIR__ . '/../api/controllers/QuizController.php';
    userSubmitAnswers();
} elseif ($route === 'get-user-results' && $method === 'GET') {
    require_once __DIR__ . '/../api/controllers/ResultController.php';
    getUserResults();
} elseif ($route === 'get_user_results' && $method === 'GET') {
    require_once __DIR__ . '/../api/controllers/ResultController.php';
    getUserResults();
} 

/**
 * Error Handling
 * ------------
 * Returns 404 for undefined routes
 */
else {
    http_response_code(404);
    echo json_encode(["error" => "Route non trouvée"]);
}

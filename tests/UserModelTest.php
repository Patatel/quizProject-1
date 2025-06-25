<?php
namespace QuizProject\Tests;

use QuizProject\Models\UserModel;
use PDO;

require_once __DIR__ . '/../api/models/UserModel.php';
require_once __DIR__ . '/TestCase.php';

class UserModelTest extends TestCase
{
    public function testCreateUserAndFindByEmail(): void
    {
        $model = new \UserModel($this->getPDO());
        $result = $model->createUser('Alice', 'alice@example.com', 'secret');
        $this->assertTrue($result, 'User should be created');

        $user = $model->findUserByEmail('alice@example.com');
        $this->assertIsArray($user);
        $this->assertSame('Alice', $user['name']);
    }

    public function testCreateUserDuplicateEmailFails(): void
    {
        $model = new \UserModel($this->getPDO());
        $this->assertTrue($model->createUser('Bob', 'bob@example.com', 'pwd'));
        $this->assertFalse($model->createUser('Bobby', 'bob@example.com', 'pwd2'));        
    }
}

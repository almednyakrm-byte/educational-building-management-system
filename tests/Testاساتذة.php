// Testاساتذة.php

namespace App\Tests\Controller;

use App\Controller\اساتذةController;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use PDO;
use PDOStatement;

class Testاساتذة extends TestCase
{
    private $controller;
    private $pdoMock;

    protected function setUp(): void
    {
        $this->pdoMock = $this->createMock(PDO::class);
        $this->controller = new اساتذةController($this->pdoMock);
    }

    public function testGetAll()
    {
        $expectedResponse = ['data' => []];
        $this->pdoMock->expects($this->once())
            ->method('query')
            ->with('SELECT * FROM اساتذة')
            ->willReturn($this->createMock(PDOStatement::class));
        $response = $this->controller->getAll();
        $this->assertEquals($expectedResponse, $response);
    }

    public function testGetById()
    {
        $id = 1;
        $expectedResponse = ['data' => []];
        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->with('SELECT * FROM اساتذة WHERE id = :id')
            ->willReturn($this->createMock(PDOStatement::class));
        $this->pdoMock->expects($this->once())
            ->method('bindParam')
            ->with(':id', $id);
        $response = $this->controller->getById($id);
        $this->assertEquals($expectedResponse, $response);
    }

    public function testCreate()
    {
        $data = ['name' => 'John Doe', 'email' => 'john@example.com'];
        $expectedResponse = ['message' => 'Teacher created successfully'];
        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO اساتذة (name, email) VALUES (:name, :email)')
            ->willReturn($this->createMock(PDOStatement::class));
        $this->pdoMock->expects($this->once())
            ->method('bindParam')
            ->with(':name', $data['name']);
        $this->pdoMock->expects($this->once())
            ->method('bindParam')
            ->with(':email', $data['email']);
        $response = $this->controller->create($data);
        $this->assertEquals($expectedResponse, $response);
    }

    public function testUpdate()
    {
        $id = 1;
        $data = ['name' => 'John Doe', 'email' => 'john@example.com'];
        $expectedResponse = ['message' => 'Teacher updated successfully'];
        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->with('UPDATE اساتذة SET name = :name, email = :email WHERE id = :id')
            ->willReturn($this->createMock(PDOStatement::class));
        $this->pdoMock->expects($this->once())
            ->method('bindParam')
            ->with(':id', $id);
        $this->pdoMock->expects($this->once())
            ->method('bindParam')
            ->with(':name', $data['name']);
        $this->pdoMock->expects($this->once())
            ->method('bindParam')
            ->with(':email', $data['email']);
        $response = $this->controller->update($id, $data);
        $this->assertEquals($expectedResponse, $response);
    }

    public function testDelete()
    {
        $id = 1;
        $expectedResponse = ['message' => 'Teacher deleted successfully'];
        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM اساتذة WHERE id = :id')
            ->willReturn($this->createMock(PDOStatement::class));
        $this->pdoMock->expects($this->once())
            ->method('bindParam')
            ->with(':id', $id);
        $response = $this->controller->delete($id);
        $this->assertEquals($expectedResponse, $response);
    }
}
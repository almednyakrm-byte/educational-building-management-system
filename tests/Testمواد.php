<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\MaterialsController;
use App\Repository\MaterialsRepository;
use PHPUnit\Framework\MockObject\MockObject;
use PDO;

class Testمواد extends TestCase
{
    private $materialsController;
    private $materialsRepository;
    private $pdo;

    protected function setUp(): void
    {
        $this->materialsRepository = $this->createMock(MaterialsRepository::class);
        $this->pdo = $this->createMock(PDO::class);
        $this->materialsController = new MaterialsController($this->materialsRepository, $this->pdo);
    }

    public function testGetMaterials()
    {
        $materials = [
            ['id' => 1, 'name' => 'Material 1'],
            ['id' => 2, 'name' => 'Material 2'],
        ];

        $this->materialsRepository->expects($this->once())
            ->method('getAllMaterials')
            ->willReturn($materials);

        $response = $this->materialsController->getMaterials();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(json_encode($materials), $response->getBody()->getContents());
    }

    public function testCreateMaterial()
    {
        $material = ['id' => 1, 'name' => 'Material 1'];

        $this->materialsRepository->expects($this->once())
            ->method('createMaterial')
            ->with($material)
            ->willReturn($material);

        $response = $this->materialsController->createMaterial($material);
        $this->assertEquals(201, $response->getStatusCode());
        $this->assertEquals(json_encode($material), $response->getBody()->getContents());
    }

    public function testUpdateMaterial()
    {
        $material = ['id' => 1, 'name' => 'Material 1'];

        $this->materialsRepository->expects($this->once())
            ->method('updateMaterial')
            ->with($material)
            ->willReturn($material);

        $response = $this->materialsController->updateMaterial($material);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(json_encode($material), $response->getBody()->getContents());
    }

    public function testDeleteMaterial()
    {
        $materialId = 1;

        $this->materialsRepository->expects($this->once())
            ->method('deleteMaterial')
            ->with($materialId)
            ->willReturn(true);

        $response = $this->materialsController->deleteMaterial($materialId);
        $this->assertEquals(204, $response->getStatusCode());
    }
}



// App\Controller\MaterialsController.php

namespace App\Controller;

use App\Repository\MaterialsRepository;
use PDO;

class MaterialsController
{
    private $materialsRepository;
    private $pdo;

    public function __construct(MaterialsRepository $materialsRepository, PDO $pdo)
    {
        $this->materialsRepository = $materialsRepository;
        $this->pdo = $pdo;
    }

    public function getMaterials()
    {
        $materials = $this->materialsRepository->getAllMaterials();
        return new \Symfony\Component\HttpFoundation\JsonResponse(json_encode($materials), 200);
    }

    public function createMaterial(array $material)
    {
        $this->materialsRepository->createMaterial($material);
        return new \Symfony\Component\HttpFoundation\JsonResponse(json_encode($material), 201);
    }

    public function updateMaterial(array $material)
    {
        $this->materialsRepository->updateMaterial($material);
        return new \Symfony\Component\HttpFoundation\JsonResponse(json_encode($material), 200);
    }

    public function deleteMaterial(int $materialId)
    {
        $this->materialsRepository->deleteMaterial($materialId);
        return new \Symfony\Component\HttpFoundation\JsonResponse(null, 204);
    }
}



// App\Repository\MaterialsRepository.php

namespace App\Repository;

class MaterialsRepository
{
    public function getAllMaterials()
    {
        // Mocked method, should be replaced with actual database query
        return [
            ['id' => 1, 'name' => 'Material 1'],
            ['id' => 2, 'name' => 'Material 2'],
        ];
    }

    public function createMaterial(array $material)
    {
        // Mocked method, should be replaced with actual database insertion
        return $material;
    }

    public function updateMaterial(array $material)
    {
        // Mocked method, should be replaced with actual database update
        return $material;
    }

    public function deleteMaterial(int $materialId)
    {
        // Mocked method, should be replaced with actual database deletion
        return true;
    }
}
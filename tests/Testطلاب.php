<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\طلابController;
use App\Repository\طلابRepository;
use App\Entity\طلاب;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\QueryException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\MockObject\MockBuilder;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class Testطلاب extends TestCase
{
    private $controller;
    private $repository;
    private $entityManager;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(طلابRepository::class);
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->entityManager->method('getRepository')->willReturn($this->repository);
        $this->controller = new طلابController($this->entityManager);
    }

    public function testGetAll()
    {
        $this->repository->method('findAll')->willReturn([new طلاب()]);
        $response = $this->controller->getAll();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testGetById()
    {
        $id = 1;
        $this->repository->method('find')->with($id)->willReturn(new طلاب());
        $response = $this->controller->getById($id);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testPost()
    {
        $request = new Request();
        $request->request->set('name', 'John Doe');
        $request->request->set('email', 'john@example.com');
        $this->repository->method('save')->with(new طلاب())->willReturn(new طلاب());
        $response = $this->controller->post($request);
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
    }

    public function testPut()
    {
        $id = 1;
        $request = new Request();
        $request->request->set('name', 'John Doe');
        $request->request->set('email', 'john@example.com');
        $this->repository->method('find')->with($id)->willReturn(new طلاب());
        $this->repository->method('flush')->willReturn(null);
        $response = $this->controller->put($id, $request);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testDelete()
    {
        $id = 1;
        $this->repository->method('find')->with($id)->willReturn(new طلاب());
        $this->repository->method('remove')->with(new طلاب())->willReturn(null);
        $this->repository->method('flush')->willReturn(null);
        $response = $this->controller->delete($id);
        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }
}



// App\Controller\طلابController.php

namespace App\Controller;

use App\Repository\طلابRepository;
use App\Entity\طلاب;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class طلابController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getAll()
    {
        $repository = $this->entityManager->getRepository(طلاب::class);
        $students = $repository->findAll();
        return new JsonResponse($students);
    }

    public function getById($id)
    {
        $repository = $this->entityManager->getRepository(طلاب::class);
        $student = $repository->find($id);
        return new JsonResponse($student);
    }

    public function post(Request $request)
    {
        $student = new طلاب();
        $student->setName($request->request->get('name'));
        $student->setEmail($request->request->get('email'));
        $repository = $this->entityManager->getRepository(طلاب::class);
        $repository->save($student);
        return new JsonResponse($student, Response::HTTP_CREATED);
    }

    public function put($id, Request $request)
    {
        $repository = $this->entityManager->getRepository(طلاب::class);
        $student = $repository->find($id);
        $student->setName($request->request->get('name'));
        $student->setEmail($request->request->get('email'));
        $repository->flush();
        return new JsonResponse($student);
    }

    public function delete($id)
    {
        $repository = $this->entityManager->getRepository(طلاب::class);
        $student = $repository->find($id);
        $repository->remove($student);
        $repository->flush();
        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}



// App\Repository\طلابRepository.php

namespace App\Repository;

use App\Entity\طلاب;
use Doctrine\ORM\EntityRepository;

class طلابRepository extends EntityRepository
{
    public function save(طلاب $student)
    {
        $this->getEntityManager()->persist($student);
        $this->getEntityManager()->flush();
        return $student;
    }

    public function find($id)
    {
        return $this->find($id);
    }

    public function remove(طلاب $student)
    {
        $this->getEntityManager()->remove($student);
        $this->getEntityManager()->flush();
    }
}



// App\Entity\طلاب.php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class طلاب
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }
}
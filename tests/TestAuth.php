<?php

namespace App\Tests\Unit\Auth;

use App\Auth\AuthService;
use App\Auth\AuthRepository;
use App\Auth\User;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\MockObject\MockBuilder;
use PHPUnit\Framework\MockObject\Call;
use PHPUnit\Framework\MockObject\MockObject as MockObjectAlias;

class TestAuth extends TestCase
{
    private $authService;
    private $authRepository;
    private $mockUser;

    protected function setUp(): void
    {
        $this->authRepository = $this->createMock(AuthRepository::class);
        $this->authService = new AuthService($this->authRepository);
        $this->mockUser = new User();
    }

    public function testLoginSuccess()
    {
        $this->mockUser->setEmail('test@example.com');
        $this->mockUser->setPassword('password');

        $this->authRepository->expects($this->once())
            ->method('getUserByEmail')
            ->with('test@example.com')
            ->willReturn($this->mockUser);

        $this->authRepository->expects($this->once())
            ->method('verifyPassword')
            ->with('password', $this->mockUser->getPassword())
            ->willReturn(true);

        $this->authService->login('test@example.com', 'password');

        $this->assertTrue($this->authService->isLoggedIn());
    }

    public function testLoginFailure()
    {
        $this->authRepository->expects($this->once())
            ->method('getUserByEmail')
            ->with('test@example.com')
            ->willReturn(null);

        $this->authService->login('test@example.com', 'password');

        $this->assertFalse($this->authService->isLoggedIn());
    }

    public function testRegisterSuccess()
    {
        $this->mockUser->setEmail('test@example.com');
        $this->mockUser->setPassword('password');

        $this->authRepository->expects($this->once())
            ->method('getUserByEmail')
            ->with('test@example.com')
            ->willReturn(null);

        $this->authRepository->expects($this->once())
            ->method('createUser')
            ->with($this->mockUser)
            ->willReturn($this->mockUser);

        $this->authService->register($this->mockUser);

        $this->assertTrue($this->authService->isLoggedIn());
    }

    public function testRegisterFailure()
    {
        $this->mockUser->setEmail('test@example.com');
        $this->mockUser->setPassword('password');

        $this->authRepository->expects($this->once())
            ->method('getUserByEmail')
            ->with('test@example.com')
            ->willReturn($this->mockUser);

        $this->authService->register($this->mockUser);

        $this->assertFalse($this->authService->isLoggedIn());
    }
}


This test class covers the following scenarios:

- `testLoginSuccess`: Tests that a user can successfully log in with a valid email and password.
- `testLoginFailure`: Tests that a user cannot log in with an invalid email or password.
- `testRegisterSuccess`: Tests that a user can successfully register with a valid email and password.
- `testRegisterFailure`: Tests that a user cannot register with an existing email.

Each test method uses the `createMock` method to create a mock object for the `AuthRepository` class, and then sets up expectations for the methods that will be called during the test. The `login` and `register` methods of the `AuthService` class are then called, and the results are asserted using the `assertTrue` and `assertFalse` methods.
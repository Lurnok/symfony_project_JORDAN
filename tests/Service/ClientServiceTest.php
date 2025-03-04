<?php

namespace App\Tests\Service;

use App\Repository\ClientRepository;
use Doctrine\ORM\AbstractQuery;
use PHPUnit\Framework\TestCase;
use App\Service\ClientService;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;

class ClientServiceTest extends TestCase
{
    public function testIsValidEmailWithValidEmail(): void{
        /** @var \App\Repository\ClientRepository&\PHPUnit\Framework\MockObject\MockObject $clientRepositoryMock */
        $clientRepositoryMock = $this->createMock(ClientRepository::class);
        $clientService = new ClientService($clientRepositoryMock);

        $this->assertTrue($clientService->isValidEmail("test@email.com"));
    }

    public function testIsValidEmailWithEmptyEmail(): void{
        /** @var \App\Repository\ClientRepository&\PHPUnit\Framework\MockObject\MockObject $clientRepositoryMock */
        $clientRepositoryMock = $this->createMock(ClientRepository::class);
        $clientService = new ClientService($clientRepositoryMock);

        $this->assertFalse($clientService->isValidEmail(""));
    }

    public function testIsValidEmailWithInvalidEmail(): void{
        /** @var \App\Repository\ClientRepository&\PHPUnit\Framework\MockObject\MockObject $clientRepositoryMock */
        $clientRepositoryMock = $this->createMock(ClientRepository::class);
        $clientService = new ClientService($clientRepositoryMock);

        $this->assertFalse($clientService->isValidEmail("invalid"));
    }

    public function testIsValidNameWithValidName(): void{
        /** @var \App\Repository\ClientRepository&\PHPUnit\Framework\MockObject\MockObject $clientRepositoryMock */
        $clientRepositoryMock = $this->createMock(ClientRepository::class);
        $clientService = new ClientService($clientRepositoryMock);

        $this->assertTrue($clientService->isValidName("name"));
    }

    public function testIsValidNameWithEmptyName(): void{
        /** @var \App\Repository\ClientRepository&\PHPUnit\Framework\MockObject\MockObject $clientRepositoryMock */
        $clientRepositoryMock = $this->createMock(ClientRepository::class);
        $clientService = new ClientService($clientRepositoryMock);

        $this->assertFalse($clientService->isValidName(""));
    }

    public function testIsValidNameWithInvalidName(): void{
        /** @var \App\Repository\ClientRepository&\PHPUnit\Framework\MockObject\MockObject $clientRepositoryMock */
        $clientRepositoryMock = $this->createMock(ClientRepository::class);
        $clientService = new ClientService($clientRepositoryMock);

        $this->assertFalse($clientService->isValidName("inv@l|d n^me!"));
    }

    public function testIsEmailAvailableWithAvailableEmail(): void{
        /** @var \App\Repository\ClientRepository&\PHPUnit\Framework\MockObject\MockObject $clientRepositoryMock */
        $clientRepositoryMock = $this->createMock(ClientRepository::class);
        $queryBuilderMock = $this->createMock(QueryBuilder::class);
        $queryMock = $this->getMockBuilder(Query::class)
            ->disableOriginalConstructor()
            ->getMock();

        $clientRepositoryMock->method('createQueryBuilder')->willReturn($queryBuilderMock);
        $queryBuilderMock->method('where')->willReturnSelf();
        $queryBuilderMock->method('setParameter')->willReturnSelf();
        $queryBuilderMock->method('getQuery')->willReturn($queryMock);

        $queryMock->method('getOneOrNullResult')->willReturn(null);
        
        
        $clientService = new ClientService($clientRepositoryMock);



        $this->assertTrue($clientService->isEmailAvailable("email@email.com"));
    }

    public function testIsEmailAvailableWithUnavailableEmail(): void{
        /** @var \App\Repository\ClientRepository&\PHPUnit\Framework\MockObject\MockObject $clientRepositoryMock */
        $clientRepositoryMock = $this->createMock(ClientRepository::class);
        $queryBuilderMock = $this->createMock(QueryBuilder::class);
        $queryMock = $this->getMockBuilder(Query::class)
            ->disableOriginalConstructor()
            ->getMock();

        $clientRepositoryMock->method('createQueryBuilder')->willReturn($queryBuilderMock);
        $queryBuilderMock->method('where')->willReturnSelf();
        $queryBuilderMock->method('setParameter')->willReturnSelf();
        $queryBuilderMock->method('getQuery')->willReturn($queryMock);

        $queryMock->method('getOneOrNullResult')->willReturn(new \stdClass());
        
        
        $clientService = new ClientService($clientRepositoryMock);



        $this->assertFalse($clientService->isEmailAvailable("email@email.com"));
    }
}

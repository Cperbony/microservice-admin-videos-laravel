<?php

namespace Tests\Unit\UseCase\Category;

use Mockery;
use stdClass;
use Ramsey\Uuid\Uuid;
use PHPUnit\Framework\TestCase;
use Core\Domain\Entity\Category;
use Core\UseCase\Category\ListCategoryUseCase;
use Core\UseCase\DTO\Category\CategoryInputDTO;
use Core\UseCase\DTO\Category\CategoryOutputDTO;
use Core\Domain\Repository\CategoryRepositoryInterface;

class ListCategoryUseCaseUnitTest extends TestCase
{

    public function testGetById()
    {
        //  Receber um DTO com dados filtráveis
        // Criar um Mock da entidade
        $id = (string) Uuid::uuid4()->toString();
        $category = 'Teste Category';

        // Mockar a entidade
        $this->mockEntity = Mockery::mock(Category::class, [
            $id,
            $category,
        ]);
        
        $this->mockEntity->shouldReceive('id')->andReturn($id);
        $this->mockEntity->shouldReceive('createdAt')->andReturn(date('Y-m-d H:i:s'));

        // Mockar o repositorio
        $this->mockRepo = Mockery::mock(stdClass::class, CategoryRepositoryInterface::class);
        $this->mockRepo->shouldReceive('findById')
            ->with($id)
            ->andReturn($this->mockEntity);

        // Mockar o DTO
        $this->mockInputDTO = Mockery::mock(CategoryInputDTO::class, [ $id ]);

        // Criar um UseCase
        $useCase = new ListCategoryUseCase($this->mockRepo);
        $response = $useCase->execute($this->mockInputDTO);
        
        $this->assertInstanceOf(CategoryOutputDTO::class, $response);
        $this->assertEquals('Teste Category', $response->name);
        $this->assertEquals($id, $response->id);
        
        /**
         * Spies
         */
        $this->spy = Mockery::spy(stdClass::class, CategoryRepositoryInterface::class);
        $this->spy->shouldReceive('findById')
              ->with($id)
              ->andReturn($this->mockEntity);
        
        $useCase = new ListCategoryUseCase($this->spy);
        $response = $useCase->execute($this->mockInputDTO);
        
        $this->spy->shouldHaveReceived('findById');
        
        
    }
    
    protected function tearDown(): void
    {
        Mockery::close();
        
        parent::tearDown();
    }

}

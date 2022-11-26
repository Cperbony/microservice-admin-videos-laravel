<?php

namespace Tests\Unit\UseCase\Category;

use Mockery;
use stdClass;
use Ramsey\Uuid\Uuid;
use PHPUnit\Framework\TestCase;
use Core\UseCase\DTO\Category\CategoryInputDTO;
use Core\UseCase\Category\DeleteCategoryUseCase;
use Core\UseCase\Category\UpdateCategoryUseCase;
use Core\Domain\Entity\Category as EntityCategory;
use Core\Domain\Repository\CategoryRepositoryInterface;
use Core\UseCase\DTO\Category\UpdateCategory\CategoryUpdateInputDTO;
use Core\UseCase\DTO\Category\DeleteCategory\CategoryDeleteOutputDTO;
use Core\UseCase\DTO\Category\UpdateCategory\CategoryUpdateOutputDTO;

class DeleteCategoryUseCaseUnitTest extends TestCase
{
    public function testDeleteCategoryTrue()
    {
  
        $uuid = Uuid::uuid4()->toString();
        
        $this->mockRepo = Mockery::mock(std::class, CategoryRepositoryInterface::class);
        $this->mockRepo->shouldReceive('delete')->andReturn(true);

        $this->mockInputDto = Mockery::mock(CategoryInputDTO::class, [$uuid]);
        
        $useCase = new DeleteCategoryUseCase($this->mockRepo);
        $responseUseCase = $useCase->execute($this->mockInputDto);

        $this->assertInstanceOf(CategoryDeleteOutputDTO::class, $responseUseCase);
        $this->assertTrue($responseUseCase->success);
        

        /**
         * Spies
         */
        // $this->spy = Mockery::spy(stdClass::class, CategoryRepositoryInterface::class);
        // $this->spy->shouldReceive('delete')->andReturn($this->mockEntity);

        // $useCase = new DeleteCategoryUseCase($this->spy);
        // $useCase->execute($this->mockInputDto);

        // $this->spy->shouldHaveReceived('delete');
    }
    
    public function testDeleteCategoryFalse()
    {
  
        $uuid = Uuid::uuid4()->toString();
        
        $this->mockRepo = Mockery::mock(std::class, CategoryRepositoryInterface::class);
        $this->mockRepo->shouldReceive('delete')->andReturn(false);

        $this->mockInputDto = Mockery::mock(CategoryInputDTO::class, [$uuid]);
        
        $useCase = new DeleteCategoryUseCase($this->mockRepo);
        $responseUseCase = $useCase->execute($this->mockInputDto);

        $this->assertInstanceOf(CategoryDeleteOutputDTO::class, $responseUseCase);
        $this->assertFalse($responseUseCase->success);
        
        /**
         * Spies
         */
        // $this->spy = Mockery::spy(stdClass::class, CategoryRepositoryInterface::class);
        // $this->spy->shouldReceive('delete')->andReturn($this->mockEntity);

        // $useCase = new DeleteCategoryUseCase($this->spy);
        // $useCase->execute($this->mockInputDto);

        // $this->spy->shouldHaveReceived('delete');
    }
    
    protected function tearDown(): void
    {
        Mockery::close();
        
        parent::tearDown();
    }
}

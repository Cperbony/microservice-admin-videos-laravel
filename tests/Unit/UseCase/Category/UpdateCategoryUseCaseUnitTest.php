<?php

namespace Tests\Unit\UseCase\Category;

use Mockery;
use stdClass;
use Ramsey\Uuid\Uuid;
use PHPUnit\Framework\TestCase;
use Core\UseCase\Category\UpdateCategoryUseCase;
use Core\Domain\Entity\Category as EntityCategory;
use Core\Domain\Repository\CategoryRepositoryInterface;
use Core\UseCase\DTO\Category\UpdateCategory\CategoryUpdateInputDTO;
use Core\UseCase\DTO\Category\UpdateCategory\CategoryUpdateOutputDTO;


class UpdateCategoryUseCaseUnitTest extends TestCase
{
    public function testRenameCategory()
    {
        $uuid = Uuid::uuid4()->toString();
        $categoryName = 'Name';
        $categoryDescription = 'Description';
        
        
        $this->mockEntity = Mockery::mock(EntityCategory::class, [
            $uuid, $categoryName, $categoryDescription 
        ]);
        
        $this->mockEntity->shouldReceive('update');
        // $this->mockEntity->shouldReceive('id')->andReturn($uuid);
        $this->mockEntity->shouldReceive('createdAt')->andReturn(date('Y-m-d H:i:s'));
        
        $this->mockRepo = Mockery::mock(stdClass::class, CategoryRepositoryInterface::class);
        $this->mockRepo->shouldReceive('findById')->andReturn($this->mockEntity);
        $this->mockRepo->shouldReceive('update')->andReturn($this->mockEntity);
        
        $this->mockInputDto = Mockery::mock(CategoryUpdateInputDTO::class, [
            $uuid, 'new name', 'new description'
        ]);
        
 
        
        $useCase = new UpdateCategoryUseCase($this->mockRepo);
        $responseUsecase = $useCase->execute($this->mockInputDto);
        
        $this->assertInstanceOf(CategoryUpdateOutputDTO::class, $responseUsecase);
        
        /**
         * Spies
         */
        $this->spy = Mockery::spy(stdClass::class, CategoryRepositoryInterface::class);
        $this->spy->shouldReceive('findById')->andReturn($this->mockEntity);
        $this->spy->shouldReceive('update')->andReturn($this->mockEntity);
        
        $useCase = new UpdateCategoryUseCase($this->spy);
        $useCase->execute($this->mockInputDto);
        
        $this->spy->shouldHaveReceived('findById');
        $this->spy->shouldHaveReceived('update');
        
        
        
        
        Mockery::close();
    }
}
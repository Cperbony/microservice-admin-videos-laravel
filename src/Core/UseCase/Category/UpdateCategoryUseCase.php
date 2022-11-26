<?php

namespace Core\UseCase\Category;

use Core\UseCase\DTO\Category\CategoryInputDTO;
use Core\UseCase\DTO\Category\CategoryOutputDTO;
use Core\Domain\Repository\CategoryRepositoryInterface;
use Core\UseCase\DTO\Category\UpdateCategory\CategoryUpdateInputDTO;
use Core\UseCase\DTO\Category\UpdateCategory\CategoryUpdateOutputDTO;

class UpdateCategoryUseCase
{
    protected $repository;

    // injetar a interface para fazer posteriormente um bind
    public function __construct(CategoryRepositoryInterface $repository)
    {
        $this->repository = $repository;

    }
    
    public function execute(CategoryUpdateInputDTO $input): CategoryUpdateOutputDTO
    {
        $category = $this->repository->findById($input->id);
        
        $category->update(
            name: $input->name,
            description: $input->description ?? $category->description
        );
        
        $categoryUpdated = $this->repository->update($category);
        
        return new CategoryUpdateOutputDTO(
            id: $categoryUpdated->id,
            name: $categoryUpdated->name,
            description: $categoryUpdated->description,
            is_active: $categoryUpdated->isActive,
            created_at: $categoryUpdated->createdAt()
        );
        
    }
}

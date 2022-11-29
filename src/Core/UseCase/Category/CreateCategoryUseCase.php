<?php

namespace Core\UseCase\Category;

use Core\Domain\Entity\Category;
use Core\Domain\Repository\CategoryRepositoryInterface;
use Core\UseCase\DTO\Category\CreateCategory\CategoryCreateInputDTO;
use Core\UseCase\DTO\Category\CreateCategory\CategoryCreateOutputDTO;
use Core\Domain\Entity\Traits\MethodsMagicsTraits;



class CreateCategoryUseCase 
{
    protected $repository;
    
    // injetar a interface para fazer posteriormente um bind
    public function __construct(CategoryRepositoryInterface $repository)
    {
         $this->repository = $repository;
        
    }
    
    
    public function execute(CategoryCreateInputDTO $input): CategoryCreateOutputDTO
    {
        $category = new Category(
            name: $input->name,
            description: $input->description,
            isActive: $input->isActive
        );
        
        $newCategory = $this->repository->insert($category);
        
        return new CategoryCreateOutputDTO(
            id: $newCategory->id(),
            name: $newCategory->name,
            description: $newCategory->description,
            is_active: $newCategory->isActive,
            created_at: $newCategory->createdAt()
        );
    }
}
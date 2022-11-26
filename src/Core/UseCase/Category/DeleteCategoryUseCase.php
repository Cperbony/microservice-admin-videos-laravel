<?php

namespace Core\UseCase\Category;

use Core\UseCase\DTO\Category\CategoryInputDTO;
use Core\UseCase\DTO\Category\CategoryOutputDTO;
use Core\Domain\Repository\CategoryRepositoryInterface;
use Core\UseCase\DTO\Category\DeleteCategory\CategoryDeleteOutputDTO;

class DeleteCategoryUseCase
{
    protected $repository;

    // injetar a interface para fazer posteriormente um bind
    public function __construct(CategoryRepositoryInterface $repository)
    {
        $this->repository = $repository;

    }
    
    public function execute(CategoryInputDTO $input): CategoryDeleteOutputDTO
    {
        $responseDelete = $this->repository->delete($input->id);
        
        return new CategoryDeleteOutputDTO(
           success: $responseDelete
        );
        
    }
}

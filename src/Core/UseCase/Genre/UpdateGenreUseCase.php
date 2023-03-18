<?php

namespace Core\UseCase\Genre;



class UpdateGenreUseCase
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

<?php

namespace Core\UseCase\Genre;

use Core\UseCase\DTO\Genre\GenreInputDTO;
use Core\UseCase\DTO\Genre\GenreOutputDTO;
use Core\UseCase\DTO\Category\CategoryOutputDTO;
use Core\Domain\Repository\GenreRepositoryInterface;
use Core\Domain\Repository\CategoryRepositoryInterface;



class ListGenreUseCase
{
    protected $repository;

    // injetar a interface para fazer posteriormente um bind
    public function __construct(GenreRepositoryInterface $repository)
    {
        $this->repository = $repository;

    }

    public function execute(GenreInputDTO $input): GenreOutputDTO
    {
        $genre = $this->repository->findById(genreId: $input->id);

        return new GenreOutputDTO(
            id: $genre->id,
            name: $genre->name,
            is_active: $genre->isActive,
            created_at: $genre->createdAt()
        );

    }
}

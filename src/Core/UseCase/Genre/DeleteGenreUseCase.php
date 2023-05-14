<?php

namespace Core\UseCase\Genre;

use Core\UseCase\DTO\Genre\GenreInputDTO;
use Core\Domain\Repository\GenreRepositoryInterface;
use Core\UseCase\DTO\Genre\DeleteGenre\GenreDeleteOutputDTO;

class DeleteGenreUseCase
{
    protected $repository;

    // injetar a interface para fazer posteriormente um bind
    public function __construct(GenreRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function execute(GenreInputDTO $input): GenreDeleteOutputDTO
    {
        $responseDelete = $this->repository->delete($input->id);

        return new GenreDeleteOutputDTO(
           success: $responseDelete
        );

    }
}

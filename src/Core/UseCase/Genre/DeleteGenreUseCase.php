<?php

namespace Core\UseCase\Genre;



class DeleteGenreUseCase
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

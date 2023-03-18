<?php

namespace Core\UseCase\Genre;

use Core\Domain\Entity\Genre;
use Core\Domain\Exception\NotFoundException;
use Core\Domain\Repository\CategoryRepositoryInterface;
use Core\Domain\Repository\GenreRepositoryInterface;
use Core\UseCase\DTO\Genre\CreateGenre\GenreCreateInputDTO;
use Core\UseCase\DTO\Genre\CreateGenre\GenreCreateOutputDTO;
use Core\UseCase\interfaces\TransactionInterface;
use Throwable;

class CreateGenreUseCase
{
    protected $repository;
    protected $transaction;
    protected $categoryRepository;

    // injetar a interface para fazer posteriormente um bind
    public function __construct(
        GenreRepositoryInterface $repository,
        TransactionInterface $transaction,
        CategoryRepositoryInterface $categoryRepository
    ) {
        $this->repository = $repository;
        $this->transaction = $transaction;
        $this->categoryRepository = $categoryRepository;
    }

    public function execute(GenreCreateInputDTO $input): GenreCreateOutputDTO
    {
        try {
            $genre = new Genre(
                name:$input->name,
                isActive:$input->isActive,
                categoriesId:$input->categoriesId
            );

            $this->validatecategoriesId($input->categoriesId);

            $genreDb = $this->repository->insert($genre);

            return new GenreCreateOutputDTO(
                id:$genreDb->id(),
                name:$genreDb->name,
                is_active:$genreDb->isActive,
                created_at:$genreDb->createdAt()
            );

            $this->transaction->commit();

        } catch (Throwable $th) {
            $this->transaction->rollback();
            throw $th;
        }
    }

    public function validatecategoriesId(array $categoriesId = [])
    {
        $categoriesDb = $this->categoryRepository->getIdsListsIds($categoriesId);

        if (count($categoriesDb) !== count($categoriesId)) {
            throw new NotFoundException('categories not found');
        }

        //foreach ($categoriesDb as $category) {

        //}

    }
}

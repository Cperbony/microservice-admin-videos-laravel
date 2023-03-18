<?php

namespace Tests\Unit\UseCase\Genre;

use Core\Domain\Repository\CategoryRepositoryInterface;
use Core\UseCase\DTO\Genre\CreateGenre\GenreCreateInputDTO;
use Core\UseCase\DTO\Genre\CreateGenre\GenreCreateOutputDTO;
use Core\UseCase\interfaces\TransactionInterface;
use Mockery;
use stdClass;
use Ramsey\Uuid\Uuid;
use Core\Domain\Entity\Genre as EntityGenre;
use PHPUnit\Framework\TestCase;
use Core\UseCase\Genre\CreateGenreUseCase;
use Core\Domain\Repository\GenreRepositoryInterface;
use Core\Domain\ValueObject\Uuid as ValueObjectUuid;

class CreateGenreUseCaseUnitTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_create()
    {
        $uuid = (string) Uuid::uuid4();
        $mockEntity = Mockery::mock(
            EntityGenre::class, [
                'teste', new ValueObjectUuid($uuid), true, [],
            ]);

        $mockEntity->shouldReceive('createdAt')->andReturn(date('Y-m-d H:i:s'));

        $mockRepository = Mockery::mock(stdClass::class, GenreRepositoryInterface::class);
        $mockRepository->shouldReceive('insert')->andReturn($mockEntity);

        $mockCreateInputDto = Mockery::mock(GenreCreateInputDTO::class, [
            'name', [$uuid], true
        ]);

        //$mockCreateOutputDto = Mockery::mock(GenreCreateOutputDTO::class, [
        //    $uuid, 'name', true, date('Y-m-d H:i:s')
        //]);

        $mockTransaction = Mockery::mock(stdClass::class, TransactionInterface::class);
        $mockTransaction->shouldReceive('commit');
        $mockTransaction->shouldReceive('rollback');

        $mockCategoryRepository = Mockery::mock(stdClass::class, CategoryRepositoryInterface::class);
        $mockCategoryRepository->shouldReceive('getIdsListsIds')->andReturn([$uuid]);

        $useCase = new CreateGenreUseCase($mockRepository, $mockTransaction, $mockCategoryRepository);
        $response = $useCase->execute($mockCreateInputDto);

        $this->assertInstanceOf(GenreCreateOutputDTO::class, $response);

        Mockery::close();
    }
}

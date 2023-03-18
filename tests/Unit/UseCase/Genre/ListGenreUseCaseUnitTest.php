<?php

namespace Tests\Unit\UseCase\Genre;

use Core\Domain\Entity\Genre as EntityGenre;
use Core\Domain\Repository\GenreRepositoryInterface;
use Core\UseCase\DTO\Genre\GenreInputDTO;
use Core\UseCase\DTO\Genre\GenreOutputDTO;
use Core\UseCase\Genre\ListGenreUseCase;
use Core\Domain\ValueObject\Uuid as ValueObjectUuid;
use Mockery;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use stdClass;

class ListGenreUseCaseUnitTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_list_single()
    {
        $uuid = (string) Uuid::uuid4();
        $mockEntity = Mockery::mock(
            EntityGenre::class, [
                'teste', new ValueObjectUuid($uuid), true, [],
            ]);

        $mockEntity->shouldReceive('createdAt')->andReturn(date('Y-m-d H:i:s'));

        $mockRepository = Mockery::mock(stdClass::class, GenreRepositoryInterface::class);
        $mockRepository->shouldReceive('findById')->andReturn($mockEntity);

        $mockInputDto = Mockery::mock(GenreInputDTO::class, [
            $uuid,
        ]);

        $useCase = new ListGenreUseCase($mockRepository);

        $response = $useCase->execute($mockInputDto);

        $this->$this->assertInstanceOf(GenreOutputDTO::class, $response);

        Mockery::close();
    }
}

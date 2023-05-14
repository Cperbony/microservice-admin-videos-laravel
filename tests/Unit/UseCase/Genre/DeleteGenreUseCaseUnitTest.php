<?php

namespace Tests\Unit\UseCase\Genre;

use Mockery;
use stdClass;
use Ramsey\Uuid\Uuid;
use PHPUnit\Framework\TestCase;
use Core\UseCase\DTO\Genre\GenreInputDTO;
use Core\UseCase\Genre\DeleteGenreUseCase;
use Core\Domain\Repository\GenreRepositoryInterface;
use Core\UseCase\DTO\Genre\DeleteGenre\GenreDeleteOutputDTO;

class DeleteGenreUseCaseUnitTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_delete()
    {
        $uuid = (string) Uuid::uuid4();

        //$mockEntity = Mockery::mock(
        //    EntityGenre::class, [
        //        'teste', new ValueObjectUuid($uuid), true, [],
        //    ]);

        $mockRepository = Mockery::mock(stdClass::class, GenreRepositoryInterface::class);
        $mockRepository->shouldReceive('delete')
            ->once()
            ->with($uuid)
            ->andReturn(true);

        $mockInputDto = Mockery::mock(GenreInputDTO::class, [
            $uuid,
        ]);

        $useCase = new DeleteGenreUseCase($mockRepository);

        $response = $useCase->execute($mockInputDto);

        $this->assertInstanceOf(GenreDeleteOutputDTO::class, $response);
        $this->assertTrue($response->success);

        Mockery::close();
    }

    public function test_delete_fail()
    {
        $uuid = (string) Uuid::uuid4();

        //$mockEntity = Mockery::mock(
        //    EntityGenre::class, [
        //        'teste', new ValueObjectUuid($uuid), true, [],
        //    ]);

        $mockRepository = Mockery::mock(stdClass::class, GenreRepositoryInterface::class);
        $mockRepository->shouldReceive('delete')
            ->once()
            ->with($uuid)
            ->andReturn(false);

        $mockInputDto = Mockery::mock(GenreInputDTO::class, [
            $uuid,
        ]);

        $useCase = new DeleteGenreUseCase($mockRepository);

        $response = $useCase->execute($mockInputDto);

        $this->assertInstanceOf(GenreDeleteOutputDTO::class, $response);
        $this->assertFalse($response->success);

        Mockery::close();
    }

    protected function tearDown(): void
    {
        Mockery::close();

        parent::tearDown();
    }
}

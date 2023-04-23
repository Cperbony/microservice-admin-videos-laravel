<?php

namespace Tests\Unit\UseCase\Genre;

use Mockery;
use stdClass;
use PHPUnit\Framework\TestCase;
use Core\UseCase\Genre\ListGenresUseCase;
use Core\Domain\Repository\PaginationInterface;
use Core\Domain\Repository\GenreRepositoryInterface;
use Core\UseCase\DTO\Genre\ListGenres\ListGenresInputDTO;
use Core\UseCase\DTO\Genre\ListGenres\ListGenresOutputDTO;



class ListGenresUseCaseUnitTest extends TestCase
{
    public function test_usecase()
    {
        $mockRepository = Mockery::mock(stdClass::class, GenreRepositoryInterface::class);
        $mockRepository->shouldReceive('paginate')->once()->andReturn($this->mockPagination());

        $mockDtoInput = Mockery::mock(ListGenresInputDTO::class, [
            'teste', 'desc', 1, 15,
        ]);

        $useCase = new ListGenresUseCase($mockRepository);
        $response = $useCase->execute($mockDtoInput);

        $this->assertInstanceOf(ListGenresOutputDTO::class, $response);

        Mockery::close();

        /**
         * Spies
         */
        // arrange
        $spy = Mockery::spy(stdClass::class, GenreRepositoryInterface::class);
        $spy->shouldReceive('paginate')->andReturn($this->mockPagination());
        $sut = new ListGenresUseCase($spy);

        // action
        $sut->execute($mockDtoInput);

        // assert
        $spy->shouldHaveReceived()->paginate(
            'teste', 'desc', 1, 15
        );
    }

    protected function mockPagination(array $items = [])
    {
        $this->mockPagination = Mockery::mock(stdClass::class, PaginationInterface::class);
        $this->mockPagination->shouldReceive('items')->andReturn($items);
        $this->mockPagination->shouldReceive('total')->andReturn(0);
        $this->mockPagination->shouldReceive('currentPage')->andReturn(0);
        $this->mockPagination->shouldReceive('firstPage')->andReturn(0);
        $this->mockPagination->shouldReceive('lastPage')->andReturn(0);
        $this->mockPagination->shouldReceive('perPage')->andReturn(0);
        $this->mockPagination->shouldReceive('to')->andReturn(0);
        $this->mockPagination->shouldReceive('from')->andReturn(0);

        return $this->mockPagination;
    }
}

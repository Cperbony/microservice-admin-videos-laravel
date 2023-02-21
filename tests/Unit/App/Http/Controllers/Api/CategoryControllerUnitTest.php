<?php

namespace Tests\Unit\App\Http\Controllers\Api;

use Core\UseCase\Category\ListCategoriesUseCase;
use Core\UseCase\DTO\Category\ListCategories\ListCategoriesOutputDTO;
use Mockery;
use Illuminate\Http\Request;
use PHPUnit\Framework\TestCase;
use App\Http\Controllers\Api\CategoryController;

class CategoryControllerUnitTest extends TestCase
{
    public function testIndex()
    {
        $mockRequest = Mockery::mock(Request::class);
        $mockRequest->shouldReceive('get')
            ->andReturn('teste');

        $mockDtoOutput = Mockery::mock(ListCategoriesOutputDTO::class, [
            [],
            1,
            1,
            1,
            1,
            1,
            1,
            1
        ]);

        $mockUseCase = Mockery::mock(ListCategoriesUseCase::class);
        $mockUseCase->shouldReceive('execute')
            ->andReturn($mockDtoOutput, $mockUseCase);


        $controller = new CategoryController();
        $response   = $controller->index($mockRequest, $mockUseCase);

        // dd($response);

        $this->assertIsObject($response->resource);
        $this->assertArrayHasKey('meta', $response->additional);
        // $this->assertTrue(true);

        /**
         * spies
         */
        $mockUseCaseSpy = Mockery::spy(ListCategoriesUseCase::class);
        $mockUseCaseSpy->shouldReceive('execute')->andReturn($mockDtoOutput, $mockUseCase);

        $response = $controller->index($mockRequest, $mockUseCaseSpy);

        $mockUseCaseSpy->shouldReceive('execute');

        Mockery::close();

    }
}

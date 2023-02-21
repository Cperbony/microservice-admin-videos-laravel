<?php

namespace Tests\Feature\App\Http\Controllers\Api;

use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Tests\TestCase;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Foundation\Testing\WithFaker;
use App\Http\Controllers\Api\CategoryController;
use Core\UseCase\Category\ListCategoriesUseCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Repositories\Eloquent\CategoryEloquentRepository;

class CategoryControllerTest extends TestCase
{

    protected $repository;

    protected function setUp(): void
    {
        $this->repository = new CategoryEloquentRepository(
            new Category()
        );

        parent::setUp();
    }
    public function test_index()
    {
        $useCase = new ListCategoriesUseCase($this->repository);

        $controller = new CategoryController();
        $response   = $controller->index(new Request, $useCase);

        // dump($response);
        $this->assertInstanceOf(AnonymousResourceCollection::class, $response);
        $this->assertArrayHasKey('meta', $response->additional);

    }
}

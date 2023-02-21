<?php

namespace Tests\Feature\App\Http\Controllers\Api;

use Tests\TestCase;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\StoreCategoryRequest;
use Illuminate\Foundation\Testing\WithFaker;
use Core\UseCase\Category\ListCategoryUseCase;
use App\Http\Controllers\Api\CategoryController;
use Core\UseCase\Category\CreateCategoryUseCase;
use Core\UseCase\Category\ListCategoriesUseCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\ParameterBag;
use App\Repositories\Eloquent\CategoryEloquentRepository;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CategoryControllerTest extends TestCase
{

    protected $repository;
    protected $controller;

    protected function setUp(): void
    {
        $this->repository = new CategoryEloquentRepository(
            new Category()
        );

        $this->controller = new CategoryController();

        parent::setUp();
    }
    public function test_index()
    {
        $useCase  = new ListCategoriesUseCase($this->repository);
        $response = $this->controller->index(new Request, $useCase);

        // dump($response);
        $this->assertInstanceOf(AnonymousResourceCollection::class, $response);
        $this->assertArrayHasKey('meta', $response->additional);
    }

    public function test_store()
    {
        $useCase = new CreateCategoryUseCase($this->repository);
        $request = new StoreCategoryRequest();
        $request->headers->set('content-type', 'application/json');
        $request->setJson(new ParameterBag([
            'name' => 'teste'
        ]));

        $response = $this->controller->store($request, $useCase);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_CREATED, $response->status());
    }

    public function test_show()
    {
        $category = Category::factory()->create();

        $response = $this->controller->show(
        useCase: new ListCategoryUseCase($this->repository),
        id: $category->id,
        );

        $this->assertEquals(Response::HTTP_OK, $response->status());
        $this->assertInstanceOf(JsonResponse::class, $response);

    }

}

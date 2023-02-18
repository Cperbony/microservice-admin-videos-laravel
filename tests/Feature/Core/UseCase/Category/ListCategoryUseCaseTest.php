<?php

namespace Tests\Feature\Core\UseCase\Category;

use Tests\TestCase;
use App\Models\Category as CategoryModel;
use Illuminate\Foundation\Testing\WithFaker;
use Core\UseCase\Category\ListCategoryUseCase;
use Core\UseCase\DTO\Category\CategoryInputDTO;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Repositories\Eloquent\CategoryEloquentRepository;

class ListCategoryUseCaseTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_list()
    {
        $categoryDb = CategoryModel::factory()->create();

        $repository = new CategoryEloquentRepository(new CategoryModel);
        $useCase    = new ListCategoryUseCase($repository);

        $responseUseCase =  $useCase->execute(new CategoryInputDTO(id: $categoryDb->id));

        $this->assertEquals($categoryDb->id, $responseUseCase->id);
        $this->assertEquals($categoryDb->name, $responseUseCase->name);
        $this->assertEquals($categoryDb->description, $responseUseCase->description);
    }
}

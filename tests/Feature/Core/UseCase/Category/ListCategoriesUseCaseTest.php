<?php

namespace Tests\Feature\Core\UseCase\Category;

use Tests\TestCase;
use App\Models\Category as CategoryModel;
use Illuminate\Foundation\Testing\WithFaker;
use Core\UseCase\Category\ListCategoryUseCase;
use Core\UseCase\Category\ListCategoriesUseCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Repositories\Eloquent\CategoryEloquentRepository;
use Core\UseCase\DTO\Category\ListCategories\ListCategoriesInputDTO;

class ListCategoriesUseCaseTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_list_empty()
    {
        $responseUseCase = $this->createUseCase();

        $this->assertCount(0, $responseUseCase->items);
        // $this->assertNotEmpty($responseUseCase->id);
        // $this->assertDatabaseHas('categories', [
        //     'id' => $responseUseCase->id
        // ]);
    }

    public function test_list_all()
    {
        $categoryDb      = CategoryModel::factory()->count(20)->create();
        $responseUseCase = $this->createUseCase();

        $this->assertCount(15, $responseUseCase->items);
        $this->assertEquals(count($categoryDb), $responseUseCase->total);

    }

    private function createUseCase()
    {
        $repository = new CategoryEloquentRepository(new CategoryModel);
        $useCase    = new ListCategoriesUseCase($repository);

        return $useCase->execute(new ListCategoriesInputDTO());
    }
}

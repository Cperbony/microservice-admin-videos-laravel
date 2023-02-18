<?php

namespace Tests\Feature\Core\UseCase\Category;

use Core\UseCase\Category\UpdateCategoryUseCase;
use Tests\TestCase;
use App\Models\Category as CategoryModel;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Repositories\Eloquent\CategoryEloquentRepository;
use Core\UseCase\DTO\Category\UpdateCategory\CategoryUpdateInputDTO;

class UpdateCategoryUseCaseTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_update()
    {
        $categoryDb = CategoryModel::factory()->create();

        $repository = new CategoryEloquentRepository(new CategoryModel());
        $useCase    = new UpdateCategoryUseCase($repository);

        $responseUseCase = $useCase->execute(
            new CategoryUpdateInputDTO(
                id: $categoryDb->id,
                name: 'Name Updated',
            )
        );

        $this->assertEquals('Name Updated', $responseUseCase->name);
        $this->assertEquals($categoryDb->description, $responseUseCase->description);

        $this->assertDatabaseHas('categories', [
            'name' => $responseUseCase->name
        ]);
    }
}

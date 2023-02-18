<?php

namespace Tests\Feature\Core\UseCase\Category;

use Tests\TestCase;
use App\Models\Category as CategoryModel;
use Illuminate\Foundation\Testing\WithFaker;
use Core\UseCase\DTO\Category\CategoryInputDTO;
use Core\UseCase\Category\DeleteCategoryUseCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Repositories\Eloquent\CategoryEloquentRepository;

class DeleteCategoryUseCaseTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_delete()
    {
        $categoryDb = CategoryModel::factory()->create();
        $repository = new CategoryEloquentRepository(new CategoryModel);
        $useCase    = new DeleteCategoryUseCase($repository);

        $responseUseCase = $useCase->execute(
            new CategoryInputDTO(
                id: $categoryDb->id
            )
        );

        $this->assertSoftDeleted($categoryDb);
    }
}

<?php

namespace Tests\Feature\Core\UseCase\Category;

use Core\UseCase\DTO\Category\CreateCategory\CategoryCreateInputDTO;
use Tests\TestCase;
use App\Models\Category as CategoryModel;
use Illuminate\Foundation\Testing\WithFaker;
use Core\UseCase\Category\CreateCategoryUseCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Repositories\Eloquent\CategoryEloquentRepository;

class CreateCategoryUseCaseTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_create()
    {
        $repository = new CategoryEloquentRepository(new CategoryModel);
        $useCase = new CreateCategoryUseCase($repository);

        $responseUseCase = $useCase->execute(
            new CategoryCreateInputDTO(
                name: 'Teste'
            )
        );

        $this->assertEquals('Teste', $responseUseCase->name);
        $this->assertNotEmpty($responseUseCase->id);
        $this->assertDatabaseHas('categories', [
            'id' => $responseUseCase->id
        ]);
    }
}

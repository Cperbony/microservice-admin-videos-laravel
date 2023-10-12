<?php

namespace Tests\Feature\Core\UseCase\Genre;

use Tests\TestCase;
use App\Models\Genre as Model;
use App\Models\Category as ModelCategory;
use Core\UseCase\Genre\CreateGenreUseCase;
use Core\Domain\Exception\NotFoundException;
use App\Repositories\Transaction\DBTransaction;
use App\Repositories\Eloquent\GenreEloquentRepository;
use App\Repositories\Eloquent\CategoryEloquentRepository;
use Core\UseCase\DTO\Genre\CreateGenre\GenreCreateInputDTO;

class CreateGenreUseCaseTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_insert()
    {
        $repository = new GenreEloquentRepository(new Model());
        $repositoryCategory = new CategoryEloquentRepository(new ModelCategory());

        $useCase = new CreateGenreUseCase(
            $repository,
            new DBTransaction(),
            $repositoryCategory
        );

        $categories = ModelCategory::factory()->count(10)->create();
        $categoriesIds = $categories->pluck('id')->toArray();

        $responseUseCase = $useCase->execute(
            new GenreCreateInputDTO(
                name: 'Teste',
                categoriesId: $categoriesIds
            )
        );

        $this->assertDatabaseHas('genres', [
            'name' => 'Teste',
        ]);
        
        $this->assertDatabaseCount('category_genre', 10);
    }
    
    public function testExceptionInsertGenreWithCategoriesIdsInvalid()
    {
      $this->expectException(NotFoundException::class);
      
        $repository = new GenreEloquentRepository(new Model());
        $repositoryCategory = new CategoryEloquentRepository(new ModelCategory());

        $useCase = new CreateGenreUseCase(
            $repository,
            new DBTransaction(),
            $repositoryCategory
        );

        $categories = ModelCategory::factory()->count(10)->create();
        $categoriesIds = $categories->pluck('id')->toArray();
        array_push($categoriesIds, 'fake_id');
        
        $responseUseCase = $useCase->execute(
            new GenreCreateInputDTO(
                name: 'Teste',
                categoriesId: $categoriesIds
            )
        );
    }
}

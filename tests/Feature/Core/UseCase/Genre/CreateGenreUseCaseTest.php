<?php

namespace Tests\Feature\Core\UseCase\Genre;

use App\Models\Category as ModelCategory;
use App\Models\Genre as Model;
use App\Repositories\Eloquent\CategoryEloquentRepository;
use App\Repositories\Eloquent\GenreEloquentRepository;
use App\Repositories\Transaction\DBTransaction;
use Core\Domain\Exception\NotFoundException;
use Core\UseCase\DTO\Genre\CreateGenre\GenreCreateInputDTO;
use Core\UseCase\Genre\CreateGenreUseCase;
use Tests\TestCase;

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

    public function testTransactionsInsert()
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

        try {
            $responseUseCase = $useCase->execute(
                new GenreCreateInputDTO(
                    name: 'Teste',
                    categoriesId: $categoriesIds
                )
            );
            $this->assertDatabaseHas('genres', [
                'name' => 'Teste',
            ]);

        } catch (\Throwable $th) {

            $this->assertDatabaseCount('genres', 0);
            $this->assertDatabaseCount('category_genre', 0);

        }

    }
}

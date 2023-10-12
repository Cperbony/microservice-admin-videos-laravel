<?php

namespace Tests\Feature\Core\UseCase\Genre;

use Tests\TestCase;
use App\Models\Genre;
use App\Models\Category as ModelCategory;
use Core\UseCase\Genre\ListGenresUseCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Repositories\Eloquent\GenreEloquentRepository;
use Core\UseCase\DTO\Genre\ListGenre\ListGenreInputDTO;
use App\Repositories\Eloquent\CategoryEloquentRepository;
use Core\UseCase\DTO\Genre\ListGenres\ListGenresInputDTO;

class ListGenresUseCaseTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testFindAll()
    {
      $repositoryCategory = new CategoryEloquentRepository(new ModelCategory());
      $useCase = new ListGenresUseCase(
          new GenreEloquentRepository(new Genre())
      );
      
      $genre = Genre::factory()->count(100)->create();
      
      $responseUseCase = $useCase->execute(
        new ListGenresInputDTO()
      );
      
      $this->assertEquals(15, count($responseUseCase->items));
      $this->assertEquals(100, $responseUseCase->total);
      
    }
}

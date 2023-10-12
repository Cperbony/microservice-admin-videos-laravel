<?php

namespace Tests\Feature\Core\UseCase\Genre;

use Tests\TestCase;
use App\Models\Genre as Model;
use Core\UseCase\Genre\ListGenreUseCase;
use App\Models\Category as ModelCategory;

use Core\UseCase\DTO\Genre\GenreInputDTO;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Repositories\Eloquent\GenreEloquentRepository;
use App\Repositories\Eloquent\CategoryEloquentRepository;

class ListGenreUseCaseTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testFindById()
    {
      $repositoryCategory = new CategoryEloquentRepository(new ModelCategory());
      $useCase = new ListGenreUseCase(
          new GenreEloquentRepository(new Model())
      );
      
      $genre = Model::factory()->create();
      
      $responseUseCase = $useCase->execute(new GenreInputDTO(
        id: $genre->id
      ));
      
      $this->assertEquals($genre->id, $responseUseCase->id);
      $this->assertEquals($genre->name, $responseUseCase->name);
    }
}

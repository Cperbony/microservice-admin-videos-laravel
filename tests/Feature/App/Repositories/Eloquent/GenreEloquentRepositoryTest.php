<?php

namespace Tests\Feature\App\Repositories\Eloquent;

use Throwable;
use Tests\TestCase;
use App\Models\Category;
use App\Models\Genre as Model;
use Core\Domain\ValueObject\Uuid;
use Ramsey\Uuid\Uuid as RamseyUuid;
use Core\Domain\Entity\Genre as EntityGenre;
use Core\Domain\Exception\NotFoundException;
use Core\Domain\Repository\PaginationInterface;
use App\Repositories\Eloquent\GenreEloquentRepository;
use App\Repositories\Eloquent\CategoryEloquentRepository;

/**
 * Summary of CategoryEloquentRepositoryTest
 * @author claudineiperboni
 * @copyright (c) 2023
 */
class GenreEloquentRepositoryTest extends TestCase
{

    protected $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new GenreEloquentRepository(new Model());
    }

    public function testImplementInterface()
    {
        $this->assertInstanceOf(GenreEloquentRepository::class, $this->repository);
    }
    public function testInsert()
    {
        $entity = new EntityGenre(
            name:'Teste Genre'
        );

        $response = $this->repository->insert($entity);

        $this->assertInstanceOf(GenreEloquentRepository::class, $this->repository);
        $this->assertInstanceOf(EntityGenre::class, $response);
        $this->assertEquals($entity->id, $response->id);
        $this->assertEquals($entity->name, $response->name);
        $this->assertDatabaseHas('genres', [
            'id' => $entity->id(),
        ]);
    }

    public function testInsertDeactivate()
    {
        $entity = new EntityGenre(
            name:'Teste Genre'
        );
        $entity->deactivate();

        $this->repository->insert($entity);

        $this->assertDatabaseHas('genres', [
            'id' => $entity->id(),
            'is_active' => false,
        ]);
    }

    /** @test */
    public function testInsertWithRelationships()
    {
        $categories = Category::factory()->count(4)->create();

        $genre = new EntityGenre(
            name:'Teste'
        );

        foreach ($categories as $category) {
            $genre->addCategory($category->id);
        }

        $response = $this->repository->insert($genre);

        $this->assertDatabaseHas('genres', [
            'id' => $response->id,
        ]);

        $this->assertDatabaseCount('category_genre', 4);

    }

    public function testFindById()
    {
        $genre = Model::factory()->create();

        $response = $this->repository->findById($genre->id);

        //dd($response);
        $this->assertInstanceOf(EntityGenre::class, $response);
        $this->assertEquals($genre->id, $response->id());
    }

    public function testFindByIdNotFound()
    {
        $this->expectException(NotFoundException::class);

        $this->repository->findById('fakevalue');

    }

    public function testFindAll()
    {
        $genres = Model::factory()->count(10)->create();

        $response = $this->repository->findAll();

        $this->assertEquals(count($genres), count($response));
    }

    public function testFindAllEmpty()
    {
        $response = $this->repository->findAll();

        $this->assertEquals(0, count($response));
    }

    public function testFindAllWithFilter()
    {
        Model::factory()->count(10)->create([
            'name' => 'genre1',
        ]);

        Model::factory()->count(10)->create();

        $response = $this->repository->findAll(
            filter:'genre1'
        );
        $this->assertEquals(10, count($response));

        $response = $this->repository->findAll();

        $this->assertEquals(20, count($response));
    }

    public function testPaginate()
    {
        $genres = Model::factory()->count(60)->create();

        $response = $this->repository->paginate();

        $this->assertInstanceOf(PaginationInterface::class, $response);
        $this->assertCount(15, $response->items());
        $this->assertEquals(60, $response->total());
    }

    //public function testUpdateNotFound()
    //{
    //    try {

    //        $category = new EntityGenre(name:'teste');
    //        $this->repository->update($category);

    //        $this->assertTrue(false);
    //    } catch (Throwable $th) {
    //        $this->assertInstanceOf(NotFoundException::class, $th);
    //    }
    //}

    public function testUpdate()
    {
        $genre = Model::factory()->create();

        $entity = new EntityGenre(
            id:new Uuid($genre->id),
            name:$genre->name,
            isActive:(bool) $genre->is_active,
            createdAt:new \DateTime ($genre->created_at)
        );

        $entity->update(
            name:'New Name Updated'
        );

        $response = $this->repository->update($entity);

        $this->assertEquals('New Name Updated', $response->name);
        $this->assertDatabaseHas('genres', [
            'name' => 'New Name Updated'
        ]);

        $this->assertTrue(true);
    }

    public function testUpdateNotFound()
    {
        $this->expectException(NotFoundException::class);

        $genreId = (string) RamseyUuid::uuid4();

        $entity = new EntityGenre(
            id:new Uuid($genreId),
            name:'Fake',
            isActive: true,
            createdAt:new \DateTime (date('Y-m-d H:i:s'))
        );

        $entity->update(
            name:'New Name Updated'
        );

        $this->repository->update($entity);

    }

    public function testDeleteIfNotFound()
    {
        try {

            $this->repository->delete('fake_id');

            $this->assertTrue(false);
        } catch (Throwable $th) {
            $this->assertInstanceOf(NotFoundException::class, $th);
        }
    }

    public function testDelete()
    {
        try {
            $genreDb = Model::factory()->create();

            $response = $this->repository->delete($genreDb->id);

            $this->assertTrue($response);
        } catch (Throwable $th) {
            $this->assertInstanceOf(NotFoundException::class, $th);
        }
    }
}

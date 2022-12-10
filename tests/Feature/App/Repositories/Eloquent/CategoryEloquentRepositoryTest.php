<?php

namespace Tests\Feature\App\Repositories\Eloquent;

use Throwable;
use Tests\TestCase;
use App\Models\Category as Model;
use Core\Domain\Exception\NotFoundException;
use Illuminate\Foundation\Testing\WithFaker;
use Core\Domain\Repository\PaginationInterface;
use Core\Domain\Entity\Category as EntityCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Core\Domain\Repository\CategoryRepositoryInterface;
use App\Repositories\Eloquent\CategoryEloquentRepository;

class CategoryEloquentRepositoryTest extends TestCase
{

    protected $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new CategoryEloquentRepository(new Model());
    }

    public function testInsert()
    {
        $entity = new EntityCategory(
            name: 'Teste Category'
        );

        $response = $this->repository->insert($entity);

        $this->assertInstanceOf(CategoryEloquentRepository::class, $this->repository);
        $this->assertInstanceOf(EntityCategory::class, $response);
        $this->assertDatabaseHas('categories', [
            'name' => $entity->name
        ]);
    }

    public function testFindById()
    {
        $category = Model::factory()->create();

        $response = $this->repository->findById($category->id);

        $this->assertInstanceOf(EntityCategory::class, $response);
        $this->assertEquals($category->id, $response->id());

    }

    public function testFindByIdNotFound()
    {
        try{
            $test = $this->repository->findById('fakevalue');
            // dump($test);
            $this->assertTrue(false);
        } catch (Throwable $th) {
            $this->assertInstanceOf(NotFoundException::class, $th);
        }
    }

    public function testFindAll()
    {
        $categories = Model::factory()->count(10)->create();

        $response = $this->repository->findAll();

        $this->assertEquals(count($categories), count($response));
    }

    public function testPaginate()
    {
        $categories = Model::factory()->count(20)->create();

        $response = $this->repository->paginate();

        $this->assertInstanceOf(PaginationInterface::class, $response);
        // $this->assertCount(15, $response->items());
    }
}

<?php

namespace Tests\Feature\App\Repositories\Eloquent;

use App\Models\Category;
use App\Repositories\Eloquent\CategoryEloquentRepository;
use Core\Domain\Entity\Category as EntityCategory;
use Core\Domain\Exception\NotFoundException;
use Core\Domain\Repository\PaginationInterface;
use Tests\TestCase;
use Throwable;

/**
 * Summary of CategoryEloquentRepositoryTest
 * @author claudineiperboni
 * @copyright (c) 2023
 */
class CategoryEloquentRepositoryTest extends TestCase
{

    protected $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new CategoryEloquentRepository(new Category());
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
            'name' => $entity->name,
        ]);
    }

    public function testFindById()
    {
        $category = Category::factory()->create();

        $response = $this->repository->findById($category->id);

        $this->assertInstanceOf(EntityCategory::class, $response);
        $this->assertEquals($category->id, $response->id());
    }

    public function testFindByIdNotFound()
    {
        try {
            $test = $this->repository->findById('fakevalue');
            $this->assertTrue(false);
        }
        catch (Throwable $th) {
            $this->assertInstanceOf(NotFoundException::class, $th);
        }
    }

    public function testFindAll()
    {
        $categories = Category::factory()->count(10)->create();

        $response = $this->repository->findAll();

        $this->assertEquals(count($categories), count($response));
    }

    public function testPaginate()
    {
        $categories = Category::factory()->count(20)->create();

        $response = $this->repository->paginate();

        $this->assertInstanceOf(PaginationInterface::class, $response);
        // $this->assertCount(15, $response->items());
    }

    public function testUpdateNotFound()
    {
        try {

            $category = new EntityCategory(name: 'teste');
            $this->repository->update($category);

            $this->assertTrue(false);
        }
        catch (Throwable $th) {
            $this->assertInstanceOf(NotFoundException::class, $th);
        }
    }

    public function testUpdate()
    {
        $categoryDb = Category::factory()->create();

        $category = new EntityCategory(
            id: $categoryDb->id,
            name: 'Updated Name'
        );

        $response = $this->repository->update($category);

        $this->assertInstanceOf(EntityCategory::class, $response);
        $this->assertNotEquals($category->name, $categoryDb->name);
        $this->assertEquals('Updated Name', $response->name);

        $this->assertTrue(true);
    }

    public function testDeleteIfNotFound()
    {
        try {

            $this->repository->delete('fake_id');

            $this->assertTrue(false);
        }
        catch (Throwable $th) {
            $this->assertInstanceOf(NotFoundException::class, $th);
        }
    }

    public function testDelete()
    {
        try {
            $categoryDb = Category::factory()->create();

            $response = $this->repository->delete($categoryDb->id);

            $this->assertTrue($response);
        }
        catch (Throwable $th) {
            $this->assertInstanceOf(NotFoundException::class, $th);
        }
    }
}

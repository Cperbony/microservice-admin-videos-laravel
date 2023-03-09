<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use App\Models\Category;
use Illuminate\Http\Response;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CategoryApiTest extends TestCase
{
    protected $endpoint;
    protected $dataJsonSctructure;
    protected $paginateStructure;
    protected function setUp(): void
    {
        $this->endpoint           = '/api/categories';
        $this->dataJsonSctructure = [
            'data' => [
                'id',
                'name',
                'description',
                'is_active',
                'created_at'
            ]
        ];

        $this->paginateStructure = [
                'meta' => [
                    'total',
                    'current_page',
                    'last_page',
                    'first_page',
                    'per_page',
                    'to',
                    'from'
                ]
        ];

        parent::setUp();
    }
    //protected $endpoint = '/api/categories';
    public function test_list_empty_categories()
    {
        $response = $this->getJson($this->endpoint);

        $response->assertStatus(200);
        $response->assertJsonCount(0, 'data');
    }

    public function test_list_all_categories()
    {
        Category::factory()->count(30)->create();

        $response = $this->getJson($this->endpoint);

        $response->assertStatus(200);
        $response->assertJsonStructure($this->paginateStructure);
        $response->assertJsonCount(15, 'data');
    }

    public function test_list_paginate_categories()
    {
        Category::factory()->count(30)->create();

        $response = $this->getJson("$this->endpoint?page=2");

        // dd("test_list_paginate_categories", $response->getData()->meta);

        $response->assertStatus(200);
        $this->assertEquals(2, $response->getData()->meta->current_page);
        $this->assertEquals(30, $response->getData()->meta->total);
        $this->assertEquals(30, $response['meta']['total']);
        $response->assertJsonCount(15, 'data');
    }

    public function test_list_category_not_found()
    {
        $response = $this->getJson("$this->endpoint/fake_value");

        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function test_list_category()
    {
        $category = Category::factory()->create();
        $response = $this->getJson("$this->endpoint/{$category->id}");

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure($this->dataJsonSctructure);

        $this->assertEquals($category->id, $response['data']['id']);
    }

    public function test_validations_store()
    {
        $data     = [];
        $response = $this->postJson($this->endpoint, $data);

        //dd($response);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonStructure([
            'message',
            'errors' => ['name'],
        ]);
    }

    public function test_store()
    {
        $data = [
            'name' => 'New category name'
        ];

        $response = $this->postJson($this->endpoint, $data);

        //dd($response);
        $response->assertStatus(Response::HTTP_CREATED);
        $response->assertJsonStructure($this->dataJsonSctructure);

        $response = $this->postJson($this->endpoint, [
            'name' => 'New Category Store',
            'description' => 'New description for new category',
            'is_active' => false
        ]);

        $response->assertStatus(Response::HTTP_CREATED);
        $this->assertEquals('New Category Store', $response['data']['name']);
        $this->assertEquals('New description for new category', $response['data']['description']);
        $this->assertDatabaseHas('categories', [
            'id' => $response['data']['id'],
            'is_active' => false
        ]);
    }

    public function test_not_found_update()
    {
        $data = [
            'name' => 'New category name for NOTFOUND'
        ];

        $response = $this->putJson("$this->endpoint/{fake_id}", $data);

        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function test_validations_update()
    {
        $data = [
            'name' => ''
        ];

        $response = $this->putJson("$this->endpoint/{fake_id}", []);
        //$response = $this->putJson($this->endpoint, $data);
        //dd($response);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonStructure([
            'message',
            'errors' => [
                'name'
            ]
        ]);
    }

    public function test_update()
    {
        $category = Category::factory()->create();
        $data = [
            'name' => 'New category FOR VALID UPDATE'
        ];

        $response = $this->putJson("$this->endpoint/{$category->id}", $data);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure($this->dataJsonSctructure);
        $this->assertDatabaseHas('categories', [
            'name' => 'New category FOR VALID UPDATE'
        ]);
    }

    public function test_not_found_delete()
    {
        $response = $this->deleteJson("$this->endpoint/fake_id");

        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function test_delete()
    {
        $category = Category::factory()->create();

        $response = $this->deleteJson("$this->endpoint/{$category->id}");

        $response->assertStatus(Response::HTTP_NO_CONTENT);
        $this->assertSoftDeleted('categories', [
            'id' => $category->id
        ]);
    }
}

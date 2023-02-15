<?php

namespace App\Repositories\Eloquent;

use Core\Domain\Entity\Category;
use App\Models\Category as Model;
use Core\Domain\Exception\NotFoundException;
use Core\Domain\Repository\PaginationInterface;
use App\Repositories\Presenters\PaginationPresenters;
use Core\Domain\Repository\CategoryRepositoryInterface;

class CategoryEloquentRepository implements CategoryRepositoryInterface
{
    protected $model;

    public function __construct(Model $category)
    {
        $this->model = $category;
    }

    public function insert(Category $category): Category
    {
        $category = $this->model->create(
            [
                'id' => $category->id,
                'name' => $category->name,
                'description' => $category->description,
                'is_active' => $category->isActive,
                'created_at' => $category->createdAt(),
            ]
        );

        return $this->toCategory($category);

    }

    public function findById(string $categoryId): Category
    {
        $category = $this->model->find($categoryId);

        // dd($category);

        if(empty($category)) {
            throw new NotFoundException();
        }

        return $this->toCategory($category);
    }

    public function getIdsListIds(array $categoryIds = []): array
    {

    }

    public function findAll(string $filter = '', $order = 'DESC'): array
    {
        $categories = $this->model
        ->where(function ($query) use ($filter){
            if($filter) {
                $query->where('name', 'LIKE', "%{$filter}%");
            }

        })->orderBy('id', $order)->get();

        return $categories->toArray();
    }

    public function paginate(string $filter = '', $order = 'DESC', int $page = 1, int $totalPage = 15): PaginationInterface
    {
        $query = $this->model;

        if ($filter) {
            $query->where('name', 'LIKE', "%{$filter}%");
        }

        $query->orderBy('id', $order);
        $paginator = $query->paginate();

        return new PaginationPresenters($paginator);

    }

    public function update(Category $category): Category
    {

        return new Category(
            name: 'update Category',
        );
    }

    public function delete(string $categoryId): bool
    {
        return true;
    }

    private function toCategory(object $object): Category
    {

        return new Category(
            id: $object->id,
            name: $object->name,
        );
    }

}

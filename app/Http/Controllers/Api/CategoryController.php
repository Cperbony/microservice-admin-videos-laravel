<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use Core\UseCase\Category\ListCategoryUseCase;
use Core\UseCase\DTO\Category\CategoryInputDTO;
use Core\UseCase\Category\CreateCategoryUseCase;
use Core\UseCase\Category\DeleteCategoryUseCase;
use Core\UseCase\Category\ListCategoriesUseCase;
use Core\UseCase\Category\UpdateCategoryUseCase;
use Core\UseCase\DTO\Category\CreateCategory\CategoryCreateInputDTO;
use Core\UseCase\DTO\Category\DeleteCategory\CategoryDeleteInputDTO;
use Core\UseCase\DTO\Category\ListCategories\ListCategoriesInputDTO;
use Core\UseCase\DTO\Category\UpdateCategory\CategoryUpdateInputDTO;

class CategoryController extends Controller
{
    public function index(Request $request, ListCategoriesUseCase $useCase)
    {
        $response = $useCase->execute(
            input: new ListCategoriesInputDTO(
                filter: $request->get('filter', ''),
                order: $request->get('order', 'DESC'),
                page: (int) $request->get('page', 1),
                totalPage: (int) $request->get('total_page', 15),
            )
        );

        return CategoryResource::collection(collect($response->items))
            ->additional([
                'meta' => [
                    'total' => $response->total,
                    'current_page' => $response->current_page,
                    'last_page' => $response->last_page,
                    'first_page' => $response->first_page,
                    'per_page' => $response->per_page,
                    'to' => $response->to,
                    'from' => $response->from,
                ],
            ]);
    }

    public function store(StoreCategoryRequest $request, CreateCategoryUseCase $useCase)
    {
        $response = $useCase->execute(
            input:new CategoryCreateInputDTO(
                name:$request->name,
                description:$request->description ?? '',
                isActive:(bool) $request->is_active ?? true,
            )
        );

        return (new CategoryResource($response))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(ListCategoryUseCase $useCase, $id)
    {
        $category = $useCase->execute(new CategoryInputDTO($id));

        //dd(collect($category));

        return (new CategoryResource($category))
            ->response();
    }

    public function update(UpdateCategoryRequest $request, $id, UpdateCategoryUseCase $useCase)
    {
        $response = $useCase->execute(new CategoryUpdateInputDTO(
            id: $id,
            name: $request->name
        ));

        return (new CategoryResource($response))
            ->response();
    }

    public function destroy(DeleteCategoryUseCase $useCase, $id)
    {
        $response = $useCase->execute(new CategoryInputDTO(
            id: $id
        ));

        return response()->noContent();
    }
}

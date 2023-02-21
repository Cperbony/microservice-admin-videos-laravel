<?php

namespace App\Http\Controllers\Api;

use Core\UseCase\Category\ListCategoryUseCase;
use Core\UseCase\DTO\Category\CategoryInputDTO;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Http\Requests\StoreCategoryRequest;
use Core\UseCase\Category\CreateCategoryUseCase;
use Core\UseCase\Category\ListCategoriesUseCase;
use Core\UseCase\DTO\Category\CreateCategory\CategoryCreateInputDTO;
use Core\UseCase\DTO\Category\ListCategories\ListCategoriesInputDTO;

class CategoryController extends Controller
{
    public function index(Request $request, ListCategoriesUseCase $useCase)
    {
        $response = $useCase->execute(
        input: new ListCategoriesInputDTO(
                filter: $request->get('filter', ''),
                order: $request->get('page', 'DESC'),
                page: (int) $request->get('page', 1),
                totalPage: (int) $request->get('total_page', 15),
            )
        );

        return CategoryResource::collection(collect($response->items))
            ->additional([
                'meta' => [
                    'totalPage'  => $response->total,
                    'last_page'  => $response->last_page,
                    'first_page' => $response->first_page,
                    'per_page'   => $response->per_page,
                    'to'         => $response->to,
                    'from'       => $response->from,
                ]
            ]);
    }

    public function store(StoreCategoryRequest $request, CreateCategoryUseCase $useCase)
    {
        $response = $useCase->execute(
        input: new CategoryCreateInputDTO(
                name: $request->name,
                description: $request->description ?? '',
                isActive: (bool) $request->is_active ?? true,
            )
        );

        return (new CategoryResource(collect($response)))
        ->response()
        ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(ListCategoryUseCase $useCase, $id)
    {
        $category = $useCase->execute(new CategoryInputDTO($id));

        return (new CategoryResource(collect($category)))
        ->response();
    }
}

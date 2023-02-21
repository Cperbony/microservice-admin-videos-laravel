<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use Core\UseCase\Category\ListCategoriesUseCase;
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
}

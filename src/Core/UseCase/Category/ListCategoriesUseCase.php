<?php

namespace Core\UseCase\Category;


use Core\Domain\Repository\CategoryRepositoryInterface;
use Core\UseCase\DTO\Category\ListCategories\ListCategoriesInputDTO;
use Core\UseCase\DTO\Category\ListCategories\ListCategoriesOutputDTO;


class ListCategoriesUseCase
{
    protected $repository;

    // injetar a interface para fazer posteriormente um bind
    public function __construct(CategoryRepositoryInterface $repository)
    {
        $this->repository = $repository;

    }
    
    public function execute(ListCategoriesInputDTO $input): ListCategoriesOutputDTO
    {
        $categories = $this->repository->paginate(
            filter: $input->filter, 
            order: $input->order,
            page: $input->page,
            totalPage: $input->totalPage,
        );
        
        return new ListCategoriesOutputDTO(
            // MAPEAR CO ARRAY-MAP, RETORNANDO SOMENTE O DESEJADO
            // items: array_map(function ($data) {
            //     return [
            //         'id' => $data->id,
            //         'name' => $data->name,
            //         'description' => $data->description,
            //         'is_active' => $data->is_active,
            //     ];
            // }),
            items: $categories->items(),
            total: $categories->total(),
            current_page: $categories->currentPage(),
            last_page: $categories->lastPage(),
            first_page: $categories->firstPage(),
            per_page: $categories->perPage(),
            to: $categories->to(),
            from: $categories->from(),
        );
        
    }
}

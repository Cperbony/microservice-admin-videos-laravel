<?php

namespace App\Repositories\Presenters;

use stdClass;
use Core\Domain\Repository\PaginationInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class PaginationPresenters implements PaginationInterface
{
    /**
     * @return stdClass[]
     */
    protected array $items = [];

    public function __construct(
        protected LengthAwarePaginator $paginator)
    {
        $this->items = $this->resolveItems(
            items: $this->paginator->items()
        );
    }



    public function items(): array
    {
    return $this->items;
    }

    public function total(): int
    {
    return $this->paginator->total();
    }

    public function lastPage(): int
    {
    return $this->paginator->lastPage();
    }

    public function firstPage(): int
    {
    return $this->paginator->firstItem();
    }

    public function currentPage(): int
    {
    return $this->paginator->currentPage();
    }

    public function perPage(): int
    {
    return $this->paginator->perPage();
    }

    public function to(): int
    {
    return $this->paginator->firstItem();
    }

    public function from(): int
    {
    return $this->paginator->lastItem();
    }

    protected function resolveItems(array $items)
    {
        $response = [];

        foreach ($items as $item) {
            $stdClass = new stdClass();

            foreach ($item->toArray() as $key => $value) {
                $stdClass ->{$key} = $value;
            }

            array_push($response, $stdClass);
        }

        return $response;

    }

}

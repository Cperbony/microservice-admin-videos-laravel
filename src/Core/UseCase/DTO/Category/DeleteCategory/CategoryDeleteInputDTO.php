<?php

namespace Core\UseCase\DTO\Category\DeleteCategory;

class CategoryDeleteInputDTO
{
    public function __construct(
        public string $name,
        public string $description = '',
        public bool $isActive = true,
    ) {
    }
}
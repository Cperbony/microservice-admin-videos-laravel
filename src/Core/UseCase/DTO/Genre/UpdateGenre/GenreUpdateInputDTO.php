<?php

namespace Core\UseCase\DTO\Genre\UpdateGenre;

class GenreUpdateInputDTO
{
    public function __construct(
        public string $id,
        public string $name,
        public string|null $description = null,
        public bool $isActive = true,
    )
    {}
}

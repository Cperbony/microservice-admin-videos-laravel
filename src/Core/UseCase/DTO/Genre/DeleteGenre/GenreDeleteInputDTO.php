<?php

namespace Core\UseCase\DTO\Genre\DeleteGenre;

class GenreDeleteInputDTO
{
    public function __construct(
        public string $name,
        public string $description = '',
        public bool $isActive = true,
    ) {
    }
}

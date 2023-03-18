<?php

namespace Core\UseCase\DTO\Genre\DeleteGenre;

class GenreDeleteOutputDTO
{
    public function __construct(
        public bool $success
    ) {
    }
}

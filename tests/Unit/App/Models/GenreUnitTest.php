<?php

namespace Tests\Unit\App\Models;


use App\Models\Genre;
use Illuminate\Database\Eloquent\Model;
use Tests\Unit\App\Models\ModelTestCase;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GenreUnitTest extends ModelTestCase
{
    protected function model(): Model
    {
        return new Genre();
    }

    protected function traits(): array
    {
        return [
            HasFactory::class,
            SoftDeletes::class,
        ];
    }

    protected function fillables(): array
    {
        return [
            'id',
            'name',
            'is_active',
            'created_at'
        ];
    }


    protected function casts(): array
    {
        return [
            'id'         => 'string',
            'is_active'  => 'boolean',
            'deleted_at' => 'datetime',
        ];
    }

}

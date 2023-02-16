<?php

namespace Tests\Unit\App\Models;


use App\Models\Category;
use Illuminate\Database\Eloquent\Model;
use Tests\Unit\App\Models\ModelTestCase;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CategoryUnitTest extends ModelTestCase
{
    protected function model(): Model
    {
        return new Category();
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
            'description',
            'is_active',
        ];
    }

    // TODO:teste plugin
    protected function casts(): array
    {
        return [
            'id'         => 'string',
            'is_active'  => 'boolean',
            'deleted_at' => 'datetime',
        ];
    }

}

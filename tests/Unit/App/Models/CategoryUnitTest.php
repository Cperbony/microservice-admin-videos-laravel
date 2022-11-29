<?php

namespace Tests\Unit\App\Models;

use App\Models\Category;
use PHPUnit\Framework\TestCase;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CategoryUnitTest extends TestCase
{
    protected function model(): Model
    {
        return new Category();
    }

    public function testIfUseTraits()
    {
        $traitsNeeds = [
            HasFactory::class,
            SoftDeletes::class,
        ];

        dump(class_uses($this->model()));

        dump(array_keys(class_uses($this->model())));

        $traitsUsed = array_keys(class_uses($this->model()));
        $this->assertTrue(true);

        $this->assertEquals($traitsNeeds, $traitsUsed);
    }
}

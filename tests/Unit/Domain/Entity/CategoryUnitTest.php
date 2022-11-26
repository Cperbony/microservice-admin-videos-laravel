<?php

namespace Tests\Unit\Domain\Entity;


use Throwable;
use Ramsey\Uuid\Uuid;
use PHPUnit\Framework\TestCase;
use Core\Domain\Entity\Category;
use Core\Domain\Exception\EntityValidationException;

class CategoryUnitTest extends TestCase
{
    /**
     * @throws EntityValidationException
     */
    public function testAttributes()
    {
        $category = new Category(
            name: 'New Cat',
            description: 'New Desc',
            isActive: true
        );

        $this->assertEquals('New Cat', $category->name);
        $this->assertEquals('New Desc', $category->description);
        $this->assertTrue(true, $category->isActive);

    }

    public function testActived()
    {
        $category = new Category(
            name: 'New cat',
            isActive: false
        );

        $this->assertFalse($category->isActive);
        $category->activate();
        $this->assertTrue($category->isActive);
    }

    public function testDisabled()
    {
        $category = new Category(
            name: 'New Cat'
        );

        $this->assertTrue($category->isActive);
        $category->disable();
        $this->assertFalse($category->isActive);
    }

    /**
     * @throws EntityValidationException
     */
    public function testUpdate()
    {
        $uuid = (string) Uuid::uuid4()->toString();

        $category = new Category(
            id: $uuid,
            name: 'New Cat',
            description: 'New Desc',
            isActive: true,
            createdAt: '2023-01-01 12:12:12'
        );

        $category->update(
            name: 'new_name',
            description: 'New task'
        );

        $this->assertEquals($uuid, $category->id());
        $this::assertEquals('new_name', $category->name);
    }

    // public function testExceptionName()
    // {
    //     try {
    //         $category = new Category(
    //             name: "Na",
    //             description: 'New Desc'
    //         );
    //         $this->assertTrue((bool)false);

    //     } catch (Throwable $th) {
    //         $this->assertInstanceOf(EntityValidationException::class, $th);

    //     }
    // }
    
    // public function testExceptionDescription()
    // {
    //     try {
    //         new Category(
    //             name: 'Name Cat',
    //             description: random_bytes(999999)
    //         );

    //         $this->assertTrue(false);
    //     } catch (Throwable $th) {
    //         $this->assertInstanceOf(EntityValidationException::class, $th);
    //     }
    // }

}
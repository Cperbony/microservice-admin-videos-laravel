<?php

namespace Tests\Unit\Domain\Entity;

use DateTime;
use Core\Domain\ValueObject\Uuid;
use Core\Domain\Entity\Genre;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid as RamseyUuid;

class GenreUnitTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testAttributes()
    {
        $uuid = (string) RamseyUuid::uuid4();
        $date = date('Y-m-d H:i:s');

        $genre = new Genre(
            id: new Uuid($uuid),
            name: 'New Genre',
            isActive: true,
            createdAt: new DateTime($date)
        );

        $this->assertEquals($uuid, $genre->id);
        $this->assertEquals('New Genre', $genre->name);
        $this->assertEquals(true, $genre->isActive);
        $this->assertEquals($date, $genre->createdAt());
    }

    public function testAttributesCreate()
    {
        $genre = new Genre(
            name: 'New Update Genre',
            isActive: false
        );

        $this->assertNotEmpty($genre->id());
        $this->assertEquals('New Update Genre', $genre->name);
        $this->assertNotEmpty($genre->createdAt());
    }

    public function testCreateActivated()
    {
        $genre = new Genre(
        name: 'Teste'
        );

        $this->assertEquals(true, $genre->isActive);
        $this->assertNotEmpty($genre->createdAt());
    }

    public function testDeactivated()
    {
        $genre = new Genre(
        name: 'Teste'
        );

        $this->assertTrue($genre->isActive);
        $genre->deactivate();
        $this->assertFalse($genre->isActive);

        $this->assertEquals(false, $genre->isActive);
        $this->assertNotEmpty($genre->createdAt());
    }

    public function testActivated()
    {
        $genre = new Genre(
        name: 'Teste',
        isActive: false
        );

        $this->assertFalse($genre->isActive);
        $genre->activate();
        $this->assertTrue($genre->isActive);

        $this->assertEquals(true, $genre->isActive);
        $this->assertNotEmpty($genre->createdAt());
    }

    public function testUpdate()
    {
        $genre = new Genre(
        name: 'Teste'
        );

        $this->assertEquals('Teste', $genre->name);

        $genre->update(
        name: 'new Genre Update'
        );

        $this->assertEquals('new Genre Update', $genre->name);
    }
}

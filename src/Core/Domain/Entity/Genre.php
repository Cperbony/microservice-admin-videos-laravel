<?php

namespace Core\Domain\Entity;

use DateTime;
use Core\Domain\ValueObject\Uuid;
use Core\Domain\Validation\DomainValidation;
use Core\Domain\Entity\Traits\MethodsMagicsTraits;
use Core\Domain\Exception\EntityValidationException;

class Genre
{
    use MethodsMagicsTraits;

    /**
     * @param string $id
     * @param string $name
     * @param bool $isActive
     * @param array $categoriesId
     * @param Datetime $createdAt
     * @throws EntityValidationException
     */

    public function __construct(
        protected string $name,
        protected ?Uuid $id = null,
        protected bool $isActive = true,
        protected array $categoriesId = [],
        protected ?DateTime $createdAt = null
    ) {
        $this->id = $this->id ?? Uuid::random();
        $this->createdAt = $this->createdAt ?? new DateTime();


        $this->validate();
    }

    public function activate()
    {
        $this->isActive = true;
    }

    public function deactivate()
    {
        $this->isActive = false;
    }

    public function update(string $name)
    {
        $this->name = $name;
        $this->validate();
    }

    public function addCategory(string $categoryId)
    {
        array_push($this->categoriesId, $categoryId);
    }

    public function removeCategory(string $categoryId)
    {
        unset($this->categoriesId[array_search($categoryId, $this->categoriesId)]);
    }

    /**
    * @throws EntityValidationException
    */
   protected function validate()
   {
       // DomainValidation::notNull($this->name);
       DomainValidation::strMaxLength($this->name);
       DomainValidation::strMinLength($this->name);
   }
}

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
     * @param string $description
     * @param bool $isActive
     * @throws EntityValidationException
     */

    public function __construct(
        protected string $name,
        protected ?Uuid $id = null,
        protected bool $isActive = true,
        protected ?DateTime $createdAt = null
    ) {
        $this->id = $this->id ?? Uuid::random();
        $this->createdAt = $this->createdAt ?? new DateTime();

        //$this->validate();
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
    }

    //public function activate(): void
    //{
    //    $this->isActive = true;
    //}

    //public function disable(): void
    //{
    //    $this->isActive = false;
    //}

    ///**
    // * @throws EntityValidationException
    // */
    //public function update(string $name, string $description = '')
    //{
    //    $this->name = $name;
    //    $this->description = $description;
    //    $this->validate();
    //}


    //private function validate()
    //{
    //    // DomainValidation::notNull($this->name);
    //    DomainValidation::strMaxLength($this->name);
    //    // DomainValidation::strMinLength($this->description);
    //    DomainValidation::strCanNullAndMaxlength($this->description, 255);
    //}

}

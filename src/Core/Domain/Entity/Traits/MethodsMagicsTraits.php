<?php

namespace Core\Domain\Entity\Traits;

use Exception;

trait MethodsMagicsTraits
{
  public function __get($property)
  {
    if (isset($this->{$property}))
      return $this->{$property};

    $className = get_class($this);
    //dd($property, $className);
    throw new Exception("Property {$property} not found {$className}", 1);

  }

  public function id(): string
  {
        return (string) $this->id;
  }

  public function createdAt(): string
  {
      return $this->createdAt->format('Y-m-d H:i:s');
  }

}

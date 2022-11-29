<?php

namespace Core\Domain\Entity\Traits;

trait MethodsMagicsTraits
{
  public function __get($property)
  {
    if (isset($this->{$property}))
      return $this->{$property};

    $className = get_class($this);
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
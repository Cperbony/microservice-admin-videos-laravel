<?php

namespace Core\UseCase\interfaces;

interface TransactionInterface
{
    public function commit();
    public function rollback();

}

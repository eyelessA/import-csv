<?php

namespace App\Services\Import;

interface ImportHandlerInterface
{
    public function import(array $clearMode);
}
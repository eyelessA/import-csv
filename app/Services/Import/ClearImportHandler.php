<?php

namespace App\Services\Import;
use Illuminate\Support\Facades\DB;

class ClearImportHandler implements ImportHandlerInterface
{
    public function import(array $clearMode): void
    {
        DB::table('images')->truncate();
        DB::table('products')->truncate();
        DB::table('categories')->truncate();
    }
}
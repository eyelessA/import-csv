<?php

namespace App\Services\Import;
class ImportHandlerFactory
{
    static public function createImportHandler(bool $clearMode): ImportHandlerInterface
    {
        if ($clearMode) {
            return new ClearImportHandler();
        }
        return new ImportHandler();
    }
}
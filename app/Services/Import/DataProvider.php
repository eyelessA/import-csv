<?php

namespace App\Services\Import;

use League\Csv\Exception;
use League\Csv\InvalidArgument;
use League\Csv\Reader;
use League\Csv\SyntaxError;
use League\Csv\UnavailableStream;

class DataProvider
{
    /**
     * @throws UnavailableStream
     * @throws InvalidArgument
     * @throws SyntaxError
     * @throws Exception
     */
    public function getData(string $filePath): \Iterator
    {
        $data = Reader::createFromPath($filePath, 'r');
        $data->setDelimiter(';');
        $data->setHeaderOffset(0);
        $header = $data->getHeader();
        return $data->getRecords();
    }
}
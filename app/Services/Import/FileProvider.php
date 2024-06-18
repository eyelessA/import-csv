<?php

namespace App\Services\Import;

use App\Exceptions\FileWasNotFoundException;
use App\Exceptions\IncorrectFileFormatException;
use Illuminate\Support\Facades\Storage;

class FileProvider
{
    /**
     * @throws \Exception
     */
    public function getFilePath(string $filename): string
    {
        $file = Storage::path('import/' . $filename);

        if (file_exists($file)) {
            $reversed = strrev($file);
            $pos = strpos($reversed, '.');
            $substring = substr($reversed, 0, $pos);
            $reversedNew = strrev($substring);

            if ($reversedNew !== 'csv') {
                throw new IncorrectFileFormatException('Ваш файл не CSV формата, мы поддерживаем только CSV');
            }
        } else {
            throw new FileWasNotFoundException("Вашего файла не существует");
        }
        return $file;
    }
}
<?php

namespace App\Console\Commands;

use App\Exceptions\FileWasNotFoundException;
use App\Exceptions\IncorrectFileFormatException;
use App\Services\Import\ClearImportHandler;
use App\Services\Import\DataProvider;
use App\Services\Import\FileProvider;
use App\Services\Import\ImportHandler;
use App\Services\Import\ImportHandlerFactory;
use Illuminate\Console\Command;
use League\Csv\Exception;
use League\Csv\InvalidArgument;
use Illuminate\Support\Facades\DB;

class ImportCsv extends Command
{
    private readonly FileProvider $fileProvider;
    private readonly DataProvider $dataProvider;
    private ClearImportHandler $clearImportHandler;
    private ImportHandler $importHandler;

    public function __construct(FileProvider $fileProvider, DataProvider $dataProvider, ClearImportHandler $clearImportHandler, ImportHandler $importHandler)
    {
        parent::__construct();
        $this->fileProvider = $fileProvider;
        $this->dataProvider = $dataProvider;
        $this->clearImportHandler = $clearImportHandler;
        $this->importHandler = $importHandler;
    }

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:import-csv {filename} {--clear-mode}';
    //php artisan app:import example_import_file.csv --clear-mode
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Performs csv import into the database';

    /**
     * Execute the console command.
     * @throws InvalidArgument
     * @throws Exception
     */
    public function handle(): void
    {
        try {
            DB::beginTransaction();
            $clearMode = $this->option('clear-mode');

            try {
                $filePath = $this->fileProvider->getFilePath($this->argument('filename'));
            } catch (FileWasNotFoundException $e) {
                $this->info($e->getMessage());
                $this->error('Пожалуйста проверьте папку storage');
                return;
            } catch (IncorrectFileFormatException $e) {
                $this->info($e->getMessage());
                $this->error('Ваш файл не CSV формата');
            }

            $records = $this->dataProvider->getData($filePath);
            $array = iterator_to_array($records);
            $clearMode === true ? $this->warn('БД успешно очистилась') : $this->info('БД успешно заполена');
            $importHandler = ImportHandlerFactory::createImportHandler($clearMode);
            $importHandler->import($array);
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            $this->warn($exception->getMessage());
        }
    }
}

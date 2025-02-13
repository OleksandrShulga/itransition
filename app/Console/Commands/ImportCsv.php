<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\CsvReaderService;
use App\Services\ProductValidator;
use App\Services\ProductImporter;

class ImportCsv extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'csv:import {file} {--test}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Імпорт файлу CSV до БД';

    /**
     * Execute the console command.
     */

    public function handle()
    {
        // Отримую шлях до файлу й перевіряю, чи була введена опція
        $filePath = $this->argument('file');
        $isTestMode = $this->option('test');

        // Перевірка, чи існує такий файл
        if (!file_exists($filePath)) {
            $this->error('Файл не знайдено');
            return;
        }

        // Згідно з принципами SOLID розкидую код по тематичним сервісам: читання, валідація і імпорт
        $csvReader = new CsvReaderService($filePath);
        $validator = new ProductValidator();
        $importer = new ProductImporter($validator, $isTestMode);

        // Безпосередньо зчитування даних з файлу та імпорт отриманих відомостей
        $records = $csvReader->getRecords();
        $result = $importer->import($records);

        // Якщо помилка — повідомляю про неї
        if (isset($result['error'])) {
            $this->error('Імпорт провалився: ' . $result['error']);
            return;
        }

        // Виведення результатів обробітку
        $this->info("Оброблено: {$result['processed']}");
        $this->info("Успішно: {$result['success']}");
        $this->info("Пропущено: {$result['skipped']}");
    }
}

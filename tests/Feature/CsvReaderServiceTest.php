<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Services\CsvReaderService;
use League\Csv\Writer;
use Illuminate\Support\Facades\Storage;

class CsvReaderServiceTest extends TestCase
{
    private string $testFilePath;

    /**
     * Налаштовую тестове середовище перед виконанням тестів.
     * Створює тестовий CSV-файл із заголовками та тестовими даними.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        // Переконуюся, що директорія існує
        if (!is_dir(storage_path('app'))) {
            mkdir(storage_path('app'), 0777, true);
        }

        // Виправлення шляху для Windows
        $this->testFilePath = storage_path('app/test.csv');

        // Створюю тестовий CSV файл
        $csv = Writer::createFromPath($this->testFilePath, 'w+');

        // Записую заголовки
        $csv->insertOne(['Product Code', 'Product Name', 'Product Description', 'Stock', 'Cost in GBP', 'Discontinued']);

        // Записую тестові дані
        $csv->insertAll([
            ['P001', 'Laptop', 'Powerful machine', '5', '999.99', ''],
            ['P002', 'Mouse', 'Wireless mouse', '50', '19.99', ''],
            ['P003', 'Keyboard', 'Mechanical keyboard', '30', '79.99', 'yes'],
        ]);
    }

    /**
     * Перевіряю, що метод getRecords() повертає iterable.
     *
     * @return void
     */
    public function testGetRecordsReturnsIterable(): void
    {
        $csvService = new CsvReaderService($this->testFilePath);
        $records = $csvService->getRecords();

        // Перевіряю, що метод повертає iterable
        $this->assertIsIterable($records);
    }

    /**
     * Видаляю тестовий CSV-файл після виконання тестів.
     *
     * @return void
     */
    protected function tearDown(): void
    {
        // Видаляю тестовий файл після завершення тесту
        if (file_exists($this->testFilePath)) {
            unlink($this->testFilePath);
        }

        parent::tearDown();
    }
}

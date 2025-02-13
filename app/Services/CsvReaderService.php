<?php

namespace App\Services;

use League\Csv\Reader;

class CsvReaderService
{
    private string $filePath;

    public function __construct(string $filePath)
    {
        $this->filePath = $filePath;
    }

    /**
     * Отримує записи з CSV файлу.
     *
     * Ця функція відкриває CSV файл за допомогою бібліотеки `Reader`, встановлює налаштування для роздільників та заголовків,
     * а потім повертає ітерабельні записи з цього файлу.
     *
     * @return iterable Повертає об'єкт ітерації з записами з CSV файлу.
     */
    public function getRecords(): iterable
    {
        // Отримую файл, за умовчанням режим r
        $csv = Reader::createFromPath($this->filePath);
        // Перший рядок це заголовки
        $csv->setHeaderOffset(0);
        // Ділю дані по комах
        $csv->setDelimiter(',');
        return $csv->getRecords();
    }
}

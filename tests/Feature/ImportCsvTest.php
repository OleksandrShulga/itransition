<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use App\Models\Product;

class ImportCsvTest extends TestCase
{

    private string $testFile = 'test.csv';

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('local');

        // Створюємо тестовий CSV-файл у storage
        Storage::disk('local')->put($this->testFile, implode("\n", [
            "strProductCode,strProductName,stock,price",
            "P001,Product A,10,99.99",
            "P002,Product B,20,49.99"
        ]));
    }

    /** @test */
    public function it_fails_when_file_does_not_exist()
    {
        $this->artisan('csv:import test.csv')
            ->expectsOutput('Файл не знайдено')
            ->assertExitCode(0);
    }
}

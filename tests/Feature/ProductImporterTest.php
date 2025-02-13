<?php

namespace Tests\Feature;

use App\Services\ProductImporter;
use App\Services\ProductValidator;
use Tests\TestCase;

class ProductImporterTest extends TestCase
{

    private ProductImporter $importer;
    private ProductValidator $validator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->validator = new ProductValidator();
        $this->importer = new ProductImporter($this->validator, true);
    }

    public function testImportProcessesValidRecords(): void
    {
        // Тестові записи продуктів
        $records = [
            [
                'Product Code' => 'P001',
                'Product Name' => 'Laptop',
                'Product Description' => 'Powerful machine',
                'Stock' => '10',
                'Cost in GBP' => '999.99',
                'Discontinued' => ''
            ],
            [
                'Product Code' => 'P002',
                'Product Name' => 'Mouse',
                'Product Description' => 'Wireless mouse',
                'Stock' => '50',
                'Cost in GBP' => '19.99',
                'Discontinued' => ''
            ],
        ];

        $result = $this->importer->import($records);

        $this->assertEquals(2, $result['processed']);
        $this->assertEquals(2, $result['success']);
        $this->assertEquals(0, $result['skipped']);
    }

    public function testImportDoesNotPersistInTestMode(): void
    {
        // Тестовий запис одного продукту
        $records = [
            [
                'Product Code' => 'P003',
                'Product Name' => 'Keyboard',
                'Product Description' => 'Mechanical keyboard',
                'Stock' => '20',
                'Cost in GBP' => '79.99',
                'Discontinued' => ''
            ],
        ];

        $this->importer->import($records);

        $this->assertDatabaseMissing('tblProductData', ['strProductCode' => 'P003']);
    }
}

<?php

namespace Tests\Unit;

use App\Services\ProductValidator;
use PHPUnit\Framework\TestCase;

class ProductValidatorTest extends TestCase
{
    private ProductValidator $validator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->validator = new ProductValidator();
    }

    /**
     * Перевіряє, що валідний запис повертає true.
     */
    public function testValidRecord(): void
    {
        $record = [
            'Cost in GBP' => 500,
            'Stock' => 20
        ];

        $this->assertTrue($this->validator->isValid($record), 'Валідний запис повертає true.');
    }

    /**
     * Перевіряє, що запис з ціною менше 5 і запасами менше 10 повертає false.
     */
    public function testInvalidRecordLowPriceAndStock(): void
    {
        $record = [
            'Cost in GBP' => 4,
            'Stock' => 5
        ];

        $this->assertFalse($this->validator->isValid($record), 'Запис з ціною < 5 і запасами < 10 повертає false.');
    }

    /**
     * Перевіряє, що запис з ціною більше 1000 повертає false.
     */
    public function testInvalidRecordHighPrice(): void
    {
        $record = [
            'Cost in GBP' => 1500,
            'Stock' => 20
        ];

        $this->assertFalse($this->validator->isValid($record), 'Запис з ціною > 1000 повертає false.');
    }

    /**
     * Перевіряє, що запис з відсутніми ціною або запасами повертає false.
     */
    public function testInvalidRecordEmptyFields(): void
    {
        // Порожня ціна
        $record = [
            'Cost in GBP' => '',
            'Stock' => 20
        ];
        $this->assertFalse($this->validator->isValid($record), 'Запис з пустими цінами повертає false.');

        // Порожні запаси
        $record = [
            'Cost in GBP' => 500,
            'Stock' => ''
        ];
        $this->assertFalse($this->validator->isValid($record), 'Запис з пустими запасами повертає false.');
    }

    /**
     * Перевіряє, що запис з ціною менше 5 і запасами більше або рівними 10 повертає true.
     */
    public function testValidRecordLowPriceHighStock(): void
    {
        $record = [
            'Cost in GBP' => 4,
            'Stock' => 10
        ];

        $this->assertTrue($this->validator->isValid($record), 'Ціна < 5 і запас >= 10 повертає true.');
    }
}

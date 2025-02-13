<?php

namespace App\Services;

use App\Models\ProductData;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ProductImporter
{
    private ProductValidator $validator;
    private bool $isTestMode;

    public function __construct(ProductValidator $validator, bool $isTestMode)
    {
        $this->validator = $validator;
        $this->isTestMode = $isTestMode;
    }

    /**
     * Імпортує записи до бази даних.
     *
     * Ця функція обробляє передані записи, перевіряє їх на валідність, а потім вносить дані до бази даних.
     * У разі тестового режиму транзакції скасовуються, інакше зміни фіксуються в базі даних.
     *
     * @param iterable $records Колекція записів, які потрібно імпортувати.
     *
     * @return array Масив з результатами імпорту:
     *               - 'processed' — кількість оброблених записів,
     *               - 'success' — кількість успішно доданих записів,
     *               - 'skipped' — кількість пропущених записів через невалідацію,
     *               - 'error' — повідомлення про помилку, якщо сталася помилка під час імпорту.
     *
     * @throws \Exception Якщо виникає помилка під час імпорту або транзакцій.
     */
    public function import(iterable $records): array
    {
        // Оброблено запитів, з них успішно і пропущені
        $processed = 0;
        $success = 0;
        $skipped = 0;

        // Міняю режим транзакцій з врахуванням можливого тестового режиму
        DB::beginTransaction();

        try {
            foreach ($records as $record) {
                // Перебираю дані і оброблюю їх позаписно
                $processed++;

                if (!$this->validator->isValid($record)) {
                    //Якщо валідація не прйдена, то пропускаю запис
                    $skipped++;
                    continue;
                }

                $productData = [
                    'strProductCode'  => $record['Product Code'],
                    'strProductName'  => $record['Product Name'],
                    'strProductDesc'  => $record['Product Description'],
                    'stock'  => $record['Stock'],
                    'price'  => preg_replace('/[^0-9.]/', '', $record['Cost in GBP']),
                    'dtmAdded' => !empty($record['Discontinued']) ? Carbon::now() : null,
                    'dtmDiscontinued'  => !empty($record['Discontinued']) ? Carbon::now() : null,
                ];

                // Вношу дані в БД, якщо все добре. Якщо є дублікат, вносяться дані, які знаходяться поближче до кінця
                if (!$this->isTestMode) {
                    ProductData::updateOrInsert(['strProductCode' => $record['Product Code']], $productData);
                }

                $success++;
            }

            // Якщо повноцінна робота, то заношу дані в БД, для тесту роблю відкат
            if (!$this->isTestMode) {
                DB::commit();
            } else {
                DB::rollBack();
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'error' => $e->getMessage(),
                'processed' => $processed,
                'success' => $success,
                'skipped' => $skipped
            ];
        }

        return compact('processed', 'success', 'skipped');
    }
}

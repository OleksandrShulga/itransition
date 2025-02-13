<?php

namespace App\Services;

class ProductValidator
{
    /**
     * Перевіряє валідність запису на основі ціни та запасів.
     *
     * Функція перевіряє, чи є у запису валідна ціна та запаси згідно з визначеними вимогами:
     * - Ціна не може бути порожньою, менше 5 або більше 1000.
     * - Запаси не можуть бути порожніми або меншими за 10, якщо ціна менша за 5.
     *
     * Ці умови забезпечують відповідність вимогам щодо мінімальних значень для ціни та запасів.
     * Додатково зазначено, що не додаються додаткові перевірки (наприклад, пусті значення замість 0),
     * оскільки це не входить у вимоги.
     *
     * @param array $record Масив, що містить дані для перевірки (ціна, запаси).
     *
     * @return bool Повертає true, якщо запис валідний, і false в іншому випадку.
     */
    public function isValid(array $record): bool
    {
        /**
         * Перевіряю на наявність проблем з отриманням ціни і запасів, а також дивлюся,
         * чи відповідає відбір вимогам ціни та запасів.
         *
         * Теоретично, можна було б придумати як протащити ще частину даних, наприклад,
         * не відсікати пусті значення запасів і ціни, а вводити туди 0. Але оскільки є
         * вимоги відбору по їх кількості, то вирішив послідувати принципу YAGNI і не робити те,
         * про що не було мови
         */
        if (empty($record['Cost in GBP']) ||
            ($record['Cost in GBP'] < 5 && $record['Stock'] < 10) ||
            $record['Cost in GBP'] > 1000 ||
            empty($record['Stock'])) {
            return false;
        }
        return true;
    }
}

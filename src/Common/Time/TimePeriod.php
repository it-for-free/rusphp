<?php

namespace ItForFree\rusphp\Common\Time;

use LogicException;

/**
 * Класс для работы с временными периодами
 */
class TimePeriod
{
    /**
     * Выделит из срока, указанного только в месяцах(int) годы и месяцы.
     * Например: 17 месяцев -> 1 год 5 месяцев
     *
     * @param int $termInMonth
      *
     * @return array
     */
    public static function changeTermFormatToStrict(int $termInMonth): array
    {
        return [
            'years' => intval($termInMonth/12),
            'months' => $termInMonth % 12,
        ];
    }

    /**
     * Сформирует строку для вывода какого-либо срока в виде "N лет M месяцев"
     * В качестве аргументов метод принимает уже рассчитанное число месяцев и лет:
     * количество месяцев не может быть больше 11. Увеличьте количество лет.
     *
     * Для выделения полных лет из общего количества месяцев можете использовать
     * метод TimePeriod::changeTermFormatToStrict()
     *
     * @param int $yearsTerm
     * @param int $monthTerm
     *
     * @return string
     *
     * @throws LogicException
     */
    public static function termToString(int $yearsTerm, int $monthTerm): string
    {
        if (1 == $monthTerm) {
            $monthString = ' месяц';
        } elseif (in_array($monthTerm, [2,3,4])) {
            $monthString = ' месяца';
        } elseif (in_array($monthTerm, [5,6,7,8,9,10,11])) {
            $monthString = ' месяцев';
        } elseif (0 == $monthTerm) {
        } else {
            throw new LogicException('Количество месяцев не может быть больше 11. Увеличьте количество лет.');
        }

        if (in_array($yearsTerm, [11,12,13,14])) {
            $yearsString = ' лет ';
        } elseif (1 == $yearsTerm % 10) {
            $yearsString = ' год ';
        } elseif (in_array($yearsTerm % 10, [2,3,4])) {
            $yearsString = ' года ';
        } elseif (0 == $yearsTerm) {
        } else {
            $yearsString = ' лет ';
        }

        if (empty($yearsTerm) && empty($monthTerm)) {
            $stringTerm = 'Не указано';
        } elseif (empty($yearsTerm)) {
            $stringTerm = $monthTerm.$monthString;
        } elseif (empty($monthTerm)) {
            $stringTerm = $yearsTerm.$yearsString;
        } else {
            $stringTerm = $yearsTerm.$yearsString.$monthTerm.$monthString;
        }

        return $stringTerm;
    }
}

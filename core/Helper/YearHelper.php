<?php

namespace Core\Helper;

class YearHelper
{
    public static function getList(int $from = 2000, int $to = null): array
    {
        $to = $to ?? (int)date('Y');
        $years = [];

        for ($year = $to; $year >= $from; $year--) {
            $years[$year] = $year;
        }

        return $years;
    }
}

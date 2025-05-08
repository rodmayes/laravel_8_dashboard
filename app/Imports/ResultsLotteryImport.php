<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Facades\Excel;

class ResultsLotteryImport implements WithMultipleSheets
{
    protected int $sheetCount;

    public function __construct(int $sheetCount)
    {
        $this->sheetCount = $sheetCount;
    }

    public function sheets(): array
    {
        $sheets = [];

        for ($i = 0; $i < $this->sheetCount; $i++) {
            $sheets[$i] = new SheetLotteryImport();
        }

        return $sheets;
    }
}


<?php

namespace App\Services\Support\Concerns;

use League\Csv\Reader;

trait ImportsCsv
{
    public function getCsvData($csvFile): array
    {
        $csv = Reader::createFromPath($csvFile, 'r');
        $csv->setHeaderOffset(0);

        $records = $csv->getRecords();

        $out = [];
        foreach ($records as $index => $row) {
            $out[] = $this->normalizeRow($row);
        }
        return $out;
    }

    protected function normalizeRow($row)
    {
        foreach ($row as $key => $value) {
            if ($value === '') {
                $row[$key] = null;
            }
        }
        return $row;
    }
}

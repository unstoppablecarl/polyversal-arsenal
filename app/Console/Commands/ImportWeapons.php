<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use League\Csv\Reader;
use League\Csv\Writer;

class ImportWeapons extends Command
{

    protected $signature = 'import:weapons';
    protected $description = 'Command description';

    public function handle()
    {
        $csv = Reader::createFromPath('source-data/weapons.csv', 'r');
        $csv->setHeaderOffset(0);

        $header  = $csv->getHeader();
        $records = $csv->getRecords();

        $data = [];

        foreach ($records as $index => $row) {
            $displayName = $row['display_name'];
            $slug        = $this->normalizeNumbers($displayName);
            $slug        = str_replace(' ', '-', $slug);
            $slug        = str_replace('.', '', $slug);
            $slug        = snake_case($slug, '-');
            $slug        = str_replace('--', '-', $slug);
            $displayName = Str::replaceFirst('Inf. ', '', $displayName);


            $newRow = [
                'id'           => $index,
                'name'         => $slug,
                'display_name' => $displayName,
                'range'        => $row['range'],
                'ap'           => $row['ap'],
                'at'           => $row['at'],
                'aa'           => $row['aa'],
                'damage'       => $row['damage'],
                'class'        => substr($slug, -1),
                'cost_d4'  => $row['cost_d4'],
                'cost_d6'  => $row['cost_d6'],
                'cost_d8'  => $row['cost_d8'],
                'cost_d10' => $row['cost_d10'],
                'cost_d12' => $row['cost_d12'],
                'is_infantry' => $row['is_infantry'] ?: null,
                'is_indirect' => $row['is_indirect'] ?: null,
                'has_warheads' => $row['has_warheads'] ?: null,
            ];

            $data[] = $newRow;
        }

        // $writer = Writer::createFromPath('source-data/weapons2.csv');

        // $first = $data[0];
        // $writer->insertOne(array_keys($first));
        //
        // $writer->insertAll($data);

        file_put_contents('source-data/imported/weapons.json', json_encode($data, JSON_NUMERIC_CHECK));
    }

    protected function normalizeNumbers($str)
    {
        $map = [
            'IV'  => 4,
            'V'   => 5,
            'III' => 3,
            'II'  => 2,
            'I'   => 1,
        ];

        foreach ($map as $roman => $number) {
            if (Str::endsWith($str, $roman)) {
                $str = Str::replaceLast($roman, $number, $str);
            }
        }
        return $str;
    }
}

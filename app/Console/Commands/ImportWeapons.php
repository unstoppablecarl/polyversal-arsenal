<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use League\Csv\Reader;

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
                'id'           => $index + 1,
                'display_name' => $displayName,
                'name'         => $slug,
                'range'        => $row['range'],
                'ap'           => 'D' . $row['ap'],
                'at'           => 'D' . $row['at_aa'],
                'aa'           => 'D' . $row['at_aa'],
                'damage'       => $row['damage'],

                'cost_d4'  => $row['D4'],
                'cost_d6'  => $row['D6'],
                'cost_d8'  => $row['D8'],
                'cost_d10' => $row['D10'],
                'cost_d12' => $row['D12'],
            ];



            $conditionals = [
              'infantry',
              'has_warheads',
              'is_indirect',
            ];

            foreach($conditionals as $conditionalKey){
                if($row[$conditionalKey]){
                    $newRow[$conditionalKey] = true;
                }
            }

            $data[] = $newRow;
        }

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

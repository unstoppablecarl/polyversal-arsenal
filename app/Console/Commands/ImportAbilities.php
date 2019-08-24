<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Validation\Rule;
use League\Csv\Reader;
use League\Csv\Writer;

class ImportAbilities extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:abilities';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $csv = Reader::createFromPath('source-data/abilities.csv', 'r');
        $csv->setHeaderOffset(0);

        $header  = $csv->getHeader();
        $records = $csv->getRecords();

        $data = [];

        foreach ($records as $index => $row) {

            $data[] = [

                'id'                   => $row['id'],
                'name'                 => $row['name'],
                'display_name'         => $row['display_name'],
                'icon'                 => $row['icon'],
                'display_order'        => $row['id'],
                'cost_static'          => $row['cost_static'] ?: null,
                'cost_infantry'        => $row['cost_infantry'] ?: null,
                'cost_cavalry'         => $row['cost_cavalry'] ?: null,
                'cost_vehicle_class_1' => $row['cost_vehicle_class_1'] ?: null,
                'cost_vehicle_class_2' => $row['cost_vehicle_class_2'] ?: null,
                'cost_vehicle_class_3' => $row['cost_vehicle_class_3'] ?: null,
                'cost_vehicle_class_4' => $row['cost_vehicle_class_4'] ?: null,
                'cost_vehicle_class_5' => $row['cost_vehicle_class_5'] ?: null,
                'infantry_valid'       => $row['infantry_valid'] ?: null,
                'cavalry_valid'        => $row['cavalry_valid'] ?: null,
                'vehicle_valid'        => $row['vehicle_valid'] ?: null,
                'warhead_cost_multiplier'        => $row['warhead_cost_multiplier'] ?: null,
            ];
        }

        file_put_contents('source-data/imported/abilities.json', json_encode($data, JSON_NUMERIC_CHECK | JSON_PRETTY_PRINT));

    }

}

<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Validation\Rule;
use League\Csv\Reader;
use League\Csv\Writer;

class Import extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import';

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
        $content = file_get_contents('source-data/static/abilities.json');

        $data = json_decode($content, true);


        $out = [];
        foreach ($data as $row) {
            $out[] = [
                'id'           => $row['id'],
                'name'         => $row['name'],
                'display_name' => $row['display_name'],
                'icon'         => '',
                'cost_static'  => array_get($row, 'cost.static', 0),

                'cost_infantry' => array_get($row, 'cost.infantry', 0),
                'cost_cavalry'  => array_get($row, 'cost.cavalry', 0),

                'cost_vehicle_class_1' => array_get($row, 'cost.vehicle.1', 0),
                'cost_vehicle_class_2' => array_get($row, 'cost.vehicle.2', 0),
                'cost_vehicle_class_3' => array_get($row, 'cost.vehicle.3', 0),
                'cost_vehicle_class_4' => array_get($row, 'cost.vehicle.4', 0),
                'cost_vehicle_class_5' => array_get($row, 'cost.vehicle.5', 0),

                'infantry_valid' => (bool)array_get($row, 'cost.infantry', 0),
                'cavalry_valid'  => (bool)array_get($row, 'cost.cavalry', 0),
                'vehicle_valid'  => (bool)array_get($row, 'cost.vehicle', 0),

                'is_defensive'            => (bool)array_get($row, 'is_defensive', 0),
                'display_order'           => (bool)array_get($row, 'display_order', 0),
                'warhead_cost_multiplier' => (bool)array_get($row, 'warhead_multiplier', 0),
            ];
        }

        $writer = Writer::createFromPath('source-data/abilities.csv');

        $first = $out[0];
        $writer->insertOne(array_keys($first));

        $writer->insertAll($out);
        // dd($vehicle);
    }

}

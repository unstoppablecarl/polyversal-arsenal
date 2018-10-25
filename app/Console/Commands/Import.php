<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Validation\Rule;
use League\Csv\Reader;

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
        $csv = Reader::createFromPath('source-data/chassis-list.csv', 'r');
        $csv->setHeaderOffset(0);

        $header  = $csv->getHeader();
        $records = $csv->getRecords();

        $replaceColumns = [
            'Class'     => 'class',
            'Mobility'  => 'mobility',
            'Armor'     => 'armor',
            'TechLevel' => 'tech_level',
            'Move'      => 'move',
            'Evasion'   => 'evasion',
            'Cost'      => 'cost',
        ];

        $replaceClasses = [
            'light'      => 1,
            'main'       => 2,
            'heavy'      => 3,
            'superheavy' => 4,
            'colossal'   => 5,
        ];

        $data = [];

        foreach ($records as $row) {
            $row = $this->replaceKeys($row, $replaceColumns);
            $row = $this->normalizeValues($row);

            $row['armor']   = (int)$row['armor'];
            $row['move']    = (int)$row['move'];
            $row['evasion'] = (int)$row['evasion'];
            $row['cost']    = (int)$row['cost'];

            $class        = $row['class'];
            $row['class'] = array_get($replaceClasses, $class) ?: $class;

            $rules = [
                'armor'      => 'integer',
                'move'       => 'integer',
                'evasion'    => 'integer',
                'tech_level' => Rule::in([
                    'primitive',
                    'typical',
                    'advanced',
                ]),
            ];

            $this->validateRow($row);
            $this->validate($row, $rules);

            $data[] = $row;
        }

        $data = $this->groupBy($data, ['class', 'mobility', 'tech_level']);
        // $data = $this->sortByArmor($data);
        $data = $this->copyMobilityCost($data);

        $infantry = $data['infantry'];
        $cavalry  = $data['cavalry'];
        $vehicle  = array_except($data, ['infantry', 'cavalry']);



        file_put_contents('./source-data/infantry-chassis.json', json_encode($infantry));
        file_put_contents('./source-data/cavalry-chassis.json', json_encode($cavalry));
        file_put_contents('./source-data/vehicle-chassis.json', json_encode($vehicle));
        // dd($vehicle);
    }

    protected function validateMobility($row, $validValues)
    {
        $rules = [
            'mobility' => Rule::in($validValues),
        ];
        $this->validate($row, $rules);
    }

    protected function groupBy($data, array $keys)
    {
        $key = array_shift($keys);

        return collect($data)
            ->groupBy($key)
            ->when($keys, function ($collection) use ($keys) {
                return $collection->map(function ($group) use ($keys) {
                    return $this->groupBy($group, $keys);
                });
            })
            ->toArray();
    }

    protected function normalizeValues($row): array
    {
        $row = array_map(function ($value) {
            $value = str_replace('-', '_', $value);
            $value = strtolower($value);
            return $value;
        }, $row);
        return $row;
    }

    protected function validateRow(array $row)
    {
        $class = $row['class'];

        if ($class == 'infantry') {
            $validValues = [
                'infantry',
            ];
        } else if ($class == 'cavalry') {
            $validValues = [
                'beast',
                'bike',
                'hoverbike',
                'trike',
            ];
        } else {
            $validValues = [
                'stationary',
                'wheeled',
                'half_tracked',
                'tracked',
                'anti_gravity',
                'walker',
                'hover',
                'vtol',
                'turbofan',
                'aerojet',
                'naval',
            ];
        }
        $this->validateMobility($row, $validValues);
    }

    protected function validate(array $data, array $rules)
    {
        $validator = \Validator::make($data, $rules);

        if ($validator->errors()->count()) {
            dd($validator->errors(), $data);
        }
    }

    protected function sortByArmor($data): array
    {
        foreach ($data as $class => $classRows) {
            foreach ($classRows as $mobility => $mobilityRows) {
                foreach ($mobilityRows as $techLevel => $techLevelRows) {
                    $data[$class][$mobility][$techLevel] = collect($techLevelRows)
                        ->sortBy('armor')
                        ->toArray();
                }
            }
        }
        return $data;
    }

    protected function replaceKeys($row, $replaceColumns): array
    {
        return array_combine(array_merge($row, $replaceColumns), $row);
    }

    protected function copyMobilityCost($data)
    {
        foreach ($data as $class => $classRows) {
            foreach ($classRows as $mobility => $mobilityRows) {
                foreach ($mobilityRows as $techLevel => $techLevelRows) {
                    $cost = collect($techLevelRows)->firstWhere('armor', 0)['cost'];

                    $data[$class][$mobility][$techLevel] = collect($techLevelRows)
                        ->sortBy('armor')

                        ->map(function($row) use($cost){
                            $row['cost'] = $cost;
                            return $row;
                        })
                        ->toArray();

                }
            }
        }
        return $data;
    }
}

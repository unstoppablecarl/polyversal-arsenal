<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Validation\Rule;
use League\Csv\Reader;

class ImportChassis extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:chassis2';

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
        $chassisRecords = $this->load('source-data/chassis.csv');
        $chassisArmorRecords = $this->load('source-data/chassis-armor-stats.csv');


        $chassisArmorRecords = $chassisArmorRecords->groupBy(function ($row) {
            return $this->chassisKey($row);
        });

        $data = [];
        foreach ($chassisRecords as $row) {
            $key = $this->chassisKey($row);
            $armorRows = $chassisArmorRecords[$key];

            foreach ($armorRows as $armorRow) {
                $armorRow = array_except($armorRow, ['cost']);
                $row = array_merge($row, $armorRow);

                $row['armor'] = (int)$row['armor'];
                $row['move'] = (int)$row['move'];
                $row['evasion'] = (int)$row['evasion'];
                $row['cost'] = (int)$row['cost'];

                $rules = [
                    'armor' => 'integer',
                    'move' => 'integer',
                    'evasion' => 'integer',
                    'tech_level' => Rule::in([
                        'primitive',
                        'typical',
                        'advanced',
                    ]),
                ];

                $this->validateRow($row);
                $this->validate($row, $rules);

                $damageTrack = [
                    'stress' => (int)$row['damage_stress'],
                    'immobilized' => (int)$row['damage_immobilized'],
                    'weapon_destroyed' => (int)$row['damage_weapon_destroyed'],
                    'targeting_destroyed' => (int)$row['damage_targeting_destroyed'],
                    'fuel_leak' => (int)$row['damage_fuel_leak'],
                    'hull_breach' => (int)$row['damage_hull_breach'],
                    'destroyed' => (int)$row['damage_destroyed'],
                ];

                $damageTrack = array_filter($damageTrack);
                $newRow = [
                    'tile_type' => $row['tile_type'],
                    'tile_class_id' => (int)$row['tile_class_id'],
                    'mobility' => $row['mobility'],
                    'tech_level' => $row['tech_level'],
                    'armor' => $row['armor'],
                    'move' => $row['move'],
                    'evasion' => $row['evasion'],
                    'cost' => $row['cost'],
                    'damage_track' => $damageTrack,
                ];
                $data[] = $newRow;
            }
        }

        // $data = collect($data)->map(function($row){
        //     // $row['class'] = $row['tile_class_id'];
        //
        //     unset($row['class']);
        //     return $row;
        // });

        $data = $this->groupBy($data, ['tile_type', 'tile_class_id', 'mobility', 'tech_level']);
        // $data = $this->sortByArmor($data);
        // $data = $this->copyMobilityCost($data);

        $infantry = $data['infantry'];
        $cavalry = $data['cavalry'];
        $vehicle = $data['vehicle'];
        $building = $data['building'];

        file_put_contents('./source-data/imported/infantry-chassis.json', json_encode($infantry, JSON_PRETTY_PRINT));
        file_put_contents('./source-data/imported/cavalry-chassis.json', json_encode($cavalry, JSON_PRETTY_PRINT));
        file_put_contents('./source-data/imported/vehicle-chassis.json', json_encode($vehicle, JSON_PRETTY_PRINT));
        file_put_contents('./source-data/imported/building-chassis.json', json_encode($building, JSON_PRETTY_PRINT));

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

    protected function validateRow(array $row)
    {
        $class = $row['tile_type'];

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
        } else if ($class == 'vehicle') {
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
        } else if ($class == 'building') {
            $validValues = [
                'stationary_building',
            ];
        }
        $this->validateMobility($row, $validValues);
    }

    protected function validateMobility($row, $validValues)
    {
        $rules = [
            'mobility' => Rule::in($validValues),
        ];
        $this->validate($row, $rules);
    }

    protected function chassisKey($row)
    {
        return implode('_', [
            $row['tile_type'],
            $row['tile_class_id'],
            $row['mobility'],
            $row['tech_level'],
        ]);
    }

    protected function validate(array $data, array $rules)
    {
        $validator = \Validator::make($data, $rules);

        if ($validator->errors()->count()) {
            dd($validator->errors(), $data);
        }
    }

    protected function load($file)
    {
        $csv = Reader::createFromPath($file, 'r');
        $csv->setHeaderOffset(0);
        $chassisRecords = $csv->getRecords();
        return collect($chassisRecords);
    }
}

<?php

use App\Models\Chassis;
use App\Models\ChassisArmorStat;
use App\Models\ChassisDamageTrack;
use App\Models\Mobility;
use App\Models\TechLevel;
use App\Models\TileType;
use App\Services\Support\Concerns\ImportsCsv;
use Illuminate\Database\Seeder;

class ChassisSeeder extends Seeder
{
    use ImportsCsv;

    protected $tileTypes;
    protected $mobilities;
    protected $techLevels;

    public function run()
    {
        $this->tileTypes  = TileType::all()->values()->keyBy('name');
        $this->mobilities = Mobility::all()->values()->keyBy('name');
        $this->techLevels = TechLevel::all()->values()->keyBy('name');

        $chassisByKey = $this->seedChassis();

        // $this->seedChassisDamageTracks($chassisByKey);
        $this->seedChassisArmorStats($chassisByKey);
    }

    protected function seedChassis(): array
    {
        $records      = $this->getCsvData('source-data/chassis.csv');
        $chassisByKey = [];

        foreach ($records as $row) {
            $row   = $this->mapRowIds($row);
            $key   = $this->keyFromRow($row);
            $row   = array_only($row, [
                'tile_type_id',
                'tile_class_id',
                'tech_level_id',
                'mobility_id',
                'cost',
                'evasion',
                'damage_stress',
                'damage_immobilized',
                'damage_weapon_destroyed',
                'damage_targeting_destroyed',
                'damage_hull_breach',
                'damage_fuel_leak',
                'damage_destroyed',
            ]);
            $where = array_only($row, [
                'tile_type_id',
                'tile_class_id',
                'tech_level_id',
                'mobility_id',
            ]);

            $chassisByKey[$key] = Chassis::query()->updateOrCreate($where, $row);
        }
        return $chassisByKey;
    }

    protected function seedChassisDamageTracks($chassisByKey)
    {
        $records = $this->getCsvData('source-data/chassis-damage-tracks.csv');

        foreach ($records as $row) {
            $row = $this->mapRowIds($row);
            $key = $this->keyFromRow($row);

            $chassis           = $chassisByKey[$key];
            $row               = array_only($row, [
                'damage_stress',
                'damage_immobilized',
                'damage_weapon_destroyed',
                'damage_targeting_destroyed',
                'damage_hull_breach',
                'damage_fuel_leak',
                'damage_destroyed',
            ]);
            $row['chassis_id'] = $chassis->id;

            $where = array_only($row, [
                'chassis_id',
            ]);

            ChassisDamageTrack::query()->updateOrCreate($where, $row);
        }
    }

    protected function seedChassisArmorStats($chassisByKey)
    {
        $records = $this->getCsvData('source-data/chassis-armor-stats.csv');

        foreach ($records as $row) {
            $row = $this->mapRowIds($row);
            $key = $this->keyFromRow($row);

            $chassis = $chassisByKey[$key];

            $row               = array_only($row, [
                'armor',
                'move',
                'cost',
            ]);
            $row['chassis_id'] = $chassis->id;


            $where = array_only($row, [
                'chassis_id',
                'armor',
            ]);
            ChassisArmorStat::query()->updateOrCreate($where, $row);
        }
    }

    protected function mapRowIds(array $row): array
    {
        $row['tile_type_id']  = $this->tileTypes->get($row['tile_type'])->id;
        $row['tech_level_id'] = $this->techLevels->get($row['tech_level'])->id;
        $row['mobility_id']   = $this->mobilities->get($row['mobility'])->id;

        return array_except($row, [
            'tile_type',
            'tech_level',
            'mobility',
        ]);
    }

    protected function keyFromRow($row): string
    {
        return implode('-', [
            $row['tile_type_id'],
            $row['tile_class_id'],
            $row['tech_level_id'],
            $row['mobility_id'],
        ]);
    }
}

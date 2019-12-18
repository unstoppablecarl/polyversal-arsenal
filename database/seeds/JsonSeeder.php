<?php

use App\Models\Ability;
use App\Models\AntiMissileSystem;
use App\Models\ArcDirection;
use App\Models\ArcSize;
use App\Models\CombatValue;
use App\Models\Mobility;
use App\Models\TechLevel;
use App\Models\TileClass;
use App\Models\TileType;
use App\Models\TileWeaponType;
use App\Models\Weapon;
use Illuminate\Database\Seeder;

class JsonSeeder extends Seeder
{

    public function run()
    {
        $map = [
            'source-data/static/arc-sizes.json'            => ArcSize::class,
            'source-data/static/arc-directions.json'       => ArcDirection::class,
            'source-data/static/anti-missile-systems.json' => AntiMissileSystem::class,
            'source-data/static/combat-values.json'        => CombatValue::class,
            'source-data/static/mobility.json'             => Mobility::class,
            'source-data/static/tech-levels.json'          => TechLevel::class,
            'source-data/static/tile-types.json'           => TileType::class,
            'source-data/static/tile-classes.json'         => TileClass::class,
            'source-data/static/tile-weapon-types.json'    => TileWeaponType::class,
        ];

        foreach ($map as $file => $modelClass) {
            $this->seedJsonData($file, $modelClass);
        }
    }

    protected function getJsonData($file): array
    {
        $content = file_get_contents($file);
        $data    = json_decode($content, true);
        return $data;
    }

    protected function seedJsonData($file, $modelClass)
    {
        $items = $this->getJsonData($file);

        foreach ($items as $item) {
            $where = array_only($item, ['id']);
            $modelClass::reguard();
            $item = (new $modelClass($item))->toArray();

            $item['id'] = $where['id'];
            $modelClass::unguard();
            $modelClass::query()->updateOrCreate($where, $item);
        }
    }
}

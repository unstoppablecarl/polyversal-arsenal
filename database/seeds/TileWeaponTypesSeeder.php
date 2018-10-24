<?php

use App\Models\TileWeaponType;
use App\Services\TileWeaponTypes;
use Illuminate\Database\Seeder;

class TileWeaponTypesSeeder extends Seeder
{
    public function run()
    {
        $items = app(TileWeaponTypes::class)->data();
        foreach ($items as $item) {
            $where = array_only($item, ['id', 'name']);

            TileWeaponType::query()->updateOrCreate($where, $item);
        }
    }
}

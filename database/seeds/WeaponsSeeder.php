<?php

use App\Models\TileWeaponType;
use App\Services\TileWeaponTypes;
use Illuminate\Database\Seeder;

class WeaponsSeeder extends Seeder
{
    public function run()
    {
        $content = file_get_contents('./database/data/weapons.json');
        $data    = json_decode($content, true);

        foreach ($data as $slug => $row) {
            $where = [
                'slug' => $slug,
            ];

            \App\Models\Weapon::query()->updateOrCreate($where, $row);
        }
    }
}

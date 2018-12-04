<?php

use App\Models\TileWeaponType;
use App\Services\Support\Concerns\ImportsCsv;
use App\Services\TileWeaponTypes;
use Illuminate\Database\Seeder;
use League\Csv\Reader;

class WeaponsSeeder extends Seeder
{
    use ImportsCsv;

    public function run()
    {
        $data = $this->getCsvData('source-data/weapons.csv');
        foreach ($data as $row) {
            $where = [
                'id' => $row['id']
            ];

            \App\Models\Weapon::query()->updateOrCreate($where, $row);
        }
    }

}

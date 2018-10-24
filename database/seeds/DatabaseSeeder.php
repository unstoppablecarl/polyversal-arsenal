<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call(ArcsSeeder::class);
        $this->call(TileWeaponTypesSeeder::class);
        $this->call(WeaponsSeeder::class);

    }
}

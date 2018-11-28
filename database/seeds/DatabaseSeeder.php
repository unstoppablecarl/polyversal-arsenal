<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call(JsonSeeder::class);
        $this->call(ChassisSeeder::class);
        $this->call(WeaponsSeeder::class);
        $this->call(AbilitiesSeeder::class);
    }
}

<?php

use App\Models\Ability;
use App\Services\Support\Concerns\ImportsCsv;
use Illuminate\Database\Seeder;

class AbilitiesSeeder extends Seeder
{
    use ImportsCsv;

    public function run()
    {
        $data = $this->getCsvData('source-data/abilities.csv');
        foreach ($data as $row) {
            $where = [
                'name' => $row['name'],
            ];

            Ability::query()->updateOrCreate($where, $row);
        }
    }
}

<?php

use App\Models\Arc;
use App\Services\Answer\Arcs;
use Illuminate\Database\Seeder;

class ArcsSeeder extends Seeder
{
    /** @var Arcs */
    protected $arcs;

    public function run()
    {
        $this->arcs = app(Arcs::class);
        $directions = [
            'UP',
            'LEFT',
            'RIGHT',
            'DOWN',
        ];

        $sizes = [
            90,
            180,
            270,
        ];

        foreach ($directions as $direction) {
            foreach ($sizes as $size) {
                $this->initArc($direction, $size);
            }
        }

        $this->initArc('X', 360);
    }

    protected function initArc($direction, $size)
    {
        Arc::query()->updateOrCreate([
            'id'        => $this->arcs->getId($direction, $size),
            'size'      => $size,
            'direction' => $direction,
        ]);
    }
}

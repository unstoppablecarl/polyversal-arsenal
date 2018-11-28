<?php

namespace Tests\Unit;

use App\Models\User;
use App\Services\CostService;
use App\Services\TileService;
use Tests\Support\RefreshAndSeedDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshAndSeedDatabase;

    public function testBasicTest()
    {
        //
        //
        // // dd($tile->tileWeapons->toArray());
        // //
        // // $this->assertEquals([1, 2, 3], $tile->abilities()->pluck('abilities.id')->toArray());
        // //
        // //
        // //
        // $z = app(CostService::class)->total($tile);
        //
        // // $z = \DB::query()->from('tile_weapons')->get();
        // //
        // dd($z);
    }
}

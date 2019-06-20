<?php

namespace App\Services;

use App\Models\User;
use Tests\TestCase;

class CostServiceTest extends TestCase
{

    public function testCosts()
    {
        $user = factory(User::class)->create();

        $input = [
            'name'                   => 'foo',
            'user_id'                => $user->id,
            'tile_type_id'           => 3,
            'tile_class_id'          => 3,
            'targeting_id'           => 1,
            'assault_id'             => 1,
            'tech_level_id'          => 2,
            'mobility_id'            => 10,
            'anti_missile_system_id' => 3,
            'armor'                  => 2,
            'stealth'                => 1,
            'cached_cost'            => 561,
            'ability_ids'            => [1, 2, 3, 16],
            'tile_weapons'           => [
                [
                    'id'                  => 'new-1',
                    'tile_weapon_type_id' => 1,
                    'arc_size_id'         => 1,
                    'arc_direction_id'    => 1,
                    'quantity'            => 4,
                    'display_order'       => 1,
                    'weapon_id'           => 1,
                ],
                [
                    'id'                  => 'new-2',
                    'tile_weapon_type_id' => 2,
                    'arc_size_id'         => 2,
                    'arc_direction_id'    => 1,
                    'quantity'            => 2,
                    'display_order'       => 1,
                    'weapon_id'           => 75,
                ],
            ],
        ];

        /** @var TileService $service */
        $service = app(TileService::class);
        $tile    = $service->create($input, $user->id);

        $service = app(CostService::class);
        $this->assertEquals(493, $service->abilitiesCost($tile));
        $this->assertEquals(52, $service->weaponCost($tile));
        $this->assertEquals(48, $service->warheadWeaponCost($tile));
        $this->assertEquals(561, $service->total($tile));

        $this->assertEquals(10, $tile->chassis->mobility_id);

    }
}

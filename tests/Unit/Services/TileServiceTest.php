<?php

namespace App\Services;

use App\Models\User;
use App\Models\Weapon;
use Tests\TestCase;

class TileServiceTest extends TestCase
{

    public function testCreate()
    {
        $user = factory(User::class)->create();

        $tileInput = [
            'name'                   => 'foo',
            'user_id'                => $user->id,
            'tile_type_id'           => 3,
            'tile_class_id'          => 3,
            'tech_level_id'          => 2,
            'mobility_id'            => 10,
            'targeting_id'           => 1,
            'assault_id'             => 1,
            'anti_missile_system_id' => 3,
            'armor'                  => 2,
            'stealth'                => 1,
            'cached_cost'            => 561,
        ];

        $tileWeapons = [
            [
                'id'                  => 'new-1',
                'tile_weapon_type_id' => 1,
                'arc_size_id'         => 1,
                'arc_direction_id'    => 2,
                'quantity'            => 4,
                'display_order'       => 1,
                'weapon_id'           => 1,
            ],
            [
                'id'                  => 'new-2',
                'tile_weapon_type_id' => 2,
                'arc_size_id'         => 2,
                'arc_direction_id'    => 2,
                'quantity'            => 2,
                'display_order'       => 1,
                'weapon_id'           => 75,
            ],
        ];

        $input = array_merge(
            $tileInput,
            [
                'ability_ids'  => [1, 2, 3, 16],
                'tile_weapons' => $tileWeapons,
            ]);

        /** @var TileService $service */
        $service = app(TileService::class);
        $tile    = $service->create($input, $user->id);

        $this->assertEquals(10, $tile->chassis->mobility_id);

        foreach ($tileWeapons as $tileWeapon) {
            $weapon = $tile->weapons->firstWhere('id', $tileWeapon['weapon_id']);

            $keys = [
                'tile_weapon_type_id',
                'arc_size_id',
                'arc_direction_id',
                'quantity',
                'display_order',
                'weapon_id',
            ];

            $expected = array_only($tileWeapon, $keys);
            $actual   = array_only($weapon->pivot->toArray(), $keys);
            $this->assertEquals($expected, $actual);
        }

        $updateInput       = [
            'tile_type_id'  => 3,
            'tile_class_id' => 4,
            'tech_level_id' => 3,
            'assault_id'    => 3,
            'mobility_id'   => 11,
        ];
        $tileInput = array_merge($tileInput, $updateInput);

        $service->update($tile, $tileInput);

        $this->assertEquals(11, $tile->chassis->mobility_id);

        $expected = array_except($updateInput, ['assault_id']);
        $keys = array_keys($expected);

        $actual = array_only($tile->chassis->toArray(), $keys);
        $this->assertEquals($expected, $actual);

    }

    public function testUpdate()
    {
    }

    public function testAddAbility()
    {
    }
}

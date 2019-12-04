<?php

namespace App\Services;

use App\Models\Tile;
use App\Models\User;
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
            'tile_print_settings'    => [
                'front_is_printer_friendly' => true,
                'front_invert_title'        => false,
                'front_cut_line_color'      => 'red',
                'front_invert_abilities'    => false,

                'back_is_printer_friendly'    => false,
                'back_invert_title'           => true,
                'back_cut_line_color'         => 'blue',
                'back_invert_bottom_headings' => true,
                'back_invert_flavor_text'     => false,
            ]
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

        $actual = $tile->tilePrintSettings->toArray();
        $actual = array_except($actual, ['tile_id', 'id']);
        $this->assertEquals($tileInput['tile_print_settings'], $actual);

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

        $updateInput = [
            'tile_type_id'  => 3,
            'tile_class_id' => 4,
            'tech_level_id' => 3,
            'assault_id'    => 3,
            'mobility_id'   => 11,
        ];
        $tileInput   = array_merge($tileInput, $updateInput);

        $service->update($tile, $tileInput);

        $this->assertEquals(11, $tile->chassis->mobility_id);

        $expected = array_except($updateInput, ['assault_id']);
        $keys     = array_keys($expected);

        $actual = array_only($tile->chassis->toArray(), $keys);
        $this->assertEquals($expected, $actual);

    }

    public function testUpdate()
    {
    }

    public function testAddAbility()
    {
    }

    public function testCopy()
    {
        $user1 = factory(User::class)->create();
        $user2 = factory(User::class)->create();


        $tileInput = [
            'name'                   => 'foo',
            'user_id'                => $user1->id,
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
            'tile_print_settings'    => [
                'front_is_printer_friendly' => true,
                'front_invert_title'        => false,
                'front_cut_line_color'      => 'red',
                'front_invert_abilities'    => false,

                'back_is_printer_friendly'    => false,
                'back_invert_title'           => true,
                'back_cut_line_color'         => 'blue',
                'back_invert_bottom_headings' => true,
                'back_invert_flavor_text'     => false,
            ]
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
        $tile    = $service->create($input, $user1->id);

        $copy = $service->copy($tile, $user2);

        $tileArray = $this->tileToNormalizedArray($tile);
        $copyArray = $this->tileToNormalizedArray($copy);

        $this->assertEquals($tileArray, $copyArray);

    }

    protected function tileToNormalizedArray(Tile $tile)
    {
        $tile = $tile->fresh();
        $tile->load([
            'abilities',
            'tilePrintSettings',
            'tileWeapons',
        ]);

        $tile = $tile->toArray();

        $tile = array_except($tile, [
            'id',
            'user_id',
            'created_at',
            'updated_at',
            'tile_print_settings.id',
            'tile_print_settings.tile_id',
            'tile_print_settings.created_at',
            'tile_print_settings.updated_at'
        ]);

        foreach ($tile['abilities'] as $index => $ability) {
            $tile['abilities'][$index] = array_except($ability, [
                'created_at',
                'updated_at',
                'tile_id',
                'pivot'
            ]);

        }
        foreach ($tile['tile_weapons'] as $index => $tileWeapon) {
            $tile['tile_weapons'][$index] = array_except($tileWeapon, [
                'id',
                'tile_id',
                'created_at',
                'updated_at',
            ]);
        }

        return $tile;
    }
}

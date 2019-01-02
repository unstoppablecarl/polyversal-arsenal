<?php

namespace App\Http\Requests;

use App\Models\Ability;
use App\Models\AntiMissileSystem;
use App\Models\Mobility;
use App\Models\Weapon;
use App\Services\TileTypes;
use App\Services\Validation\Base64ImageRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TileSaveRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $tileTypeIds          = [1, 2, 3];
        $tileClassIds         = [1, 2, 3, 4, 5];
        $targetingIds         = [1, 2, 3, 4, 5];
        $assaultIds           = [1, 2, 3, 4, 5];
        $techLevelIds         = [1, 2, 3];
        $mobilityIds          = Mobility::query()->pluck('id')->toArray();
        $antiMissileSystemIds = AntiMissileSystem::query()->pluck('id')->toArray();
        $abilityIds           = Ability::query()->pluck('id')->toArray();
        $weaponIds            = Weapon::wherePublic()->pluck('id')->toArray();
        $weaponTypeIds        = [1, 2, 3];
        $arcSizeIds           = [1, 2, 3, 4];
        $arcDirectionIds      = [1, 2, 3, 4];


        $maxStealth = $this->maxStealth();

        if ($maxStealth) {
            $maxStealth = '|max:' . $maxStealth;
        } else {
            $maxStealth = '';
        }

        return [
            'name'                   => 'required',
            'app_version'            => Rule::in([1]),
            'tile_type_id'           => Rule::in($tileTypeIds),
            'tile_class_id'          => Rule::in($tileClassIds),
            'targeting_id'           => Rule::in($targetingIds),
            'assault_id'             => Rule::in($assaultIds),
            'tech_level_id'          => Rule::in($techLevelIds),
            'mobility_id'            => Rule::in($mobilityIds),
            'anti_missile_system_id' => Rule::in($antiMissileSystemIds),
            'armor'                  => 'integer',
            'stealth'                => 'integer|min:0' . $maxStealth,
            'cached_cost'            => 'integer',

            'ability_ids'   => 'array',
            'ability_ids.*' => Rule::in($abilityIds),

            'tile_weapons'                       => 'array',
            'tile_weapons.*.weapon_id'           => Rule::in($weaponIds),
            'tile_weapons.*.tile_weapon_type_id' => Rule::in($weaponTypeIds),
            'tile_weapons.*.arc_direction_id'    => Rule::in($arcDirectionIds),
            'tile_weapons.*.arc_size_id'         => Rule::in($arcSizeIds),
            'tile_weapons.*.quantity'            => 'integer|min:1',
            'tile_weapons.*.display_order'       => 'integer',

            'costs'              => 'array',
            'costs.total'        => 'integer|required',
            'costs.stats'        => 'integer|required',
            'costs.tile_weapons' => 'integer|required',
            'costs.abilities'    => 'integer|required',

            'new_front_image'        => [
                'nullable',
                new Base64ImageRule,
            ],
            'new_back_image'         => [
                'nullable',
                new Base64ImageRule,
            ],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $this->validateAbilityIds($validator);
        });
    }

    protected function validateAbilityIds($validator)
    {
        $abilityIds = $this->input('ability_ids', []);
        $tileTypeId = $this->input('tile_type_id');

        $hasTileTypeIdError = $validator->errors()->get('tile_type_id');

        if ($hasTileTypeIdError || !$abilityIds) {
            return;
        }

        $tileTypeDisplayName = app(TileTypes::class)->idToDisplayNameOrFail($tileTypeId);

        $abilities = Ability::query()
            ->whereIn('id', $abilityIds)
            ->get();

        foreach ($abilities as $ability) {
            if (!$ability->isValidTileType($tileTypeId)) {
                $validator->errors()->add('abilities', "The '{$ability->display_name}' Ability, cannot be assigned to tile type: '{$tileTypeDisplayName}'");
            }
        }
    }

    protected function maxStealth(): int
    {
        $tileClassId = $this->input('tile_class_id');
        $tileTypeId  = $this->input('tile_type_id');

        if (!$tileTypeId) {
            return 0;
        }
        if ($tileTypeId === TileTypes::VEHICLE_ID) {
            $maxStealth = $tileClassId;
        } else if ($tileTypeId === TileTypes::INFANTRY_ID) {
            $maxStealth = 2;
        } else if ($tileTypeId === TileTypes::CAVALRY_ID) {
            $maxStealth = 3;
        }

        return $maxStealth;
    }
}

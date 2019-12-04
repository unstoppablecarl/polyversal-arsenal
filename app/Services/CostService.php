<?php

namespace App\Services;

use App\Models\Ability;
use App\Models\Tile;
use App\Models\TileWeapon;
use App\Services\Exceptions\InvalidAbilityTileTypeException;
use Illuminate\Support\Collection;

class CostService
{
    public function total(Tile $tile)
    {
        $cost          = $this->statsCost($tile);
        $weaponCost    = $this->weaponCost($tile);
        $abilitiesCost = $this->abilitiesCost($tile);

        return $cost + $weaponCost + $abilitiesCost;
    }

    public function statsCost(Tile $tile): int
    {
        $chassis = $tile->chassis;

        $chassisArmorStat = $tile->getChassisArmorStat();
        $cost             = 0;

        $cost += (int)$tile->assault_id;
        $cost += (int)$tile->targeting_id;
        $cost += (int)$chassis->cost;
        $cost += (int)$chassisArmorStat->cost;
        return $cost;
    }

    public function weaponCost(Tile $tile)
    {
        $targetingName = $tile->targeting->name;

        $tile->loadMissing(['tileWeapons.weapon']);
        $tile->load('tileWeapons.arcSize');
        $tile->load('tileWeapons.tileWeaponType');

        /** @var Collection $weapons */
        $weapons = $tile->tileWeapons;
        return $weapons->sum(function (TileWeapon $tileWeapon) use ($targetingName) {
            $column        = 'cost_' . $targetingName;
            $attackCost    = $tileWeapon->weapon[$column];
            $arcMod        = $tileWeapon->arcSize->cost_multiplier;
            $weaponTypeMod = $this->tileWeaponTypeCost($tileWeapon->tileWeaponType->name);
            $baseCost      = round($attackCost * $arcMod);
            $cost          = round($baseCost * $weaponTypeMod);
            return max($cost, 1) * $tileWeapon->quantity;
        });
    }

    protected function warheadWeaponCost(Tile $tile)
    {
        $tile->loadMissing(['tileWeapons.weapon']);

        /** @var Collection $weapons */
        $weapons = $tile->tileWeapons;
        return $weapons->sum(function (TileWeapon $tileWeapon) {
            $weapon = $tileWeapon->weapon;
            $class = (int)$tileWeapon->weapon->class;

            if ($weapon->has_warheads) {
                return $class * (int)$tileWeapon->quantity;
            }
        });
    }

    public function getCostDiff($tile, array $costs)
    {
        $total        = array_get($costs, 'total');
        $tile_weapons = array_get($costs, 'tile_weapons');
        $abilities    = array_get($costs, 'abilities');
        $stats        = array_get($costs, 'stats');

        $results = [
            [

                'key' => 'total',
                'vue' => $total,
                'app' => $this->total($tile),
            ],
            [

                'key' => 'tile_weapons',
                'vue' => $tile_weapons,
                'app' => $this->weaponCost($tile),
            ],
            [
                'key' => 'abilities',
                'vue' => $abilities,
                'app' => $this->abilitiesCost($tile),
            ],
            [
                'key' => 'stats',
                'vue' => $stats,
                'app' => $this->statsCost($tile),
            ],
        ];

        $diff = [];

        foreach ($results as $row) {
            if ($row['vue'] != $row['app']) {
                $diff[] = $row;
            }
        }

        return $diff;
    }

    public function abilitiesCost(Tile $tile)
    {
        $cost          = 0;
        $abilitiesCost = $tile->abilities->sum(function (Ability $ability) use ($tile) {
            return $this->abilityCost($tile, $ability);
        });

        $cost += $abilitiesCost;
        $cost += (int)$tile->stealth;
        $cost += (int)$tile->antiMissileSystem->cost;

        return $cost;
    }

    public function abilityCost(Tile $tile, Ability $ability): int
    {
        $tileTypeId = $tile->chassis->tile_type_id;

        if (!$ability->isValidTileType($tileTypeId)) {
            throw new InvalidAbilityTileTypeException($ability, $tileTypeId);
        }

        if ($ability->cost_static) {
            return $ability->cost_static;
        }

        if ($tileTypeId == TileTypes::INFANTRY_ID) {
            return $ability->cost_infantry;
        }

        if ($tileTypeId == TileTypes::CAVALRY_ID) {
            return $ability->cost_cavalry;
        }

        if ($tileTypeId == TileTypes::VEHICLE_ID) {

            if ($ability->warhead_cost_multiplier > 0) {
                return $this->warheadWeaponCost($tile) * (float)$ability->warhead_cost_multiplier;
            }

            $tileClassId = $tile->chassis->tile_class_id;
            $key         = 'cost_vehicle_class_' . $tileClassId;
            return $ability[$key];
        }
    }

    public function tileWeaponTypeCost(string $tileWeaponTypeName)
    {
        $map = [
            'ground'  => 5 / 6,
            'with_aa' => 1,
            'only_aa' => 1 / 6,
        ];

        return $map[$tileWeaponTypeName];
    }
}

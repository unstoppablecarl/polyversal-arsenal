<?php

namespace App\Services;

class ChassisQueryService
{

    public function getAll()
    {
        return \DB::query()
            ->selectRaw('

tile_types.name as tile_type,
tile_classes.display_name as tile_class,
mobilities.display_name as tile_mobility,
tech_levels.display_name as tech_level,
chassis_armor_stats.armor,
chassis_armor_stats.move,
chassis.evasion,
chassis_damage_tracks.stress,
chassis_damage_tracks.immobilized,
chassis_damage_tracks.hull_breach,
chassis_damage_tracks.targeting_destroyed,
chassis_damage_tracks.fuel_leak,
chassis_damage_tracks.weapon_destroyed,
chassis_damage_tracks.destroyed,
chassis.cost
            ')
            ->from('chassis')
            ->join('tile_types', 'tile_types.id', '=', 'chassis.tile_type_id')
            ->join('tile_classes', 'tile_classes.id', '=', 'chassis.tile_class_id')
            ->join('mobilities', 'mobilities.id', '=', 'chassis.mobility_id')
            ->join('tech_levels', 'tech_levels.id', '=', 'chassis.tech_level_id')
            ->join('chassis_armor_stats', 'chassis_armor_stats.chassis_id', '=', 'chassis.id')
            ->join('chassis_damage_tracks', 'chassis_damage_tracks.chassis_id', '=', 'chassis.id')
            ->orderBy('armor')
            ->orderBy('tile_classes.id')
            ->orderBy('tile_types.id')
            ->orderBy('mobilities.id')
            ->orderBy('tech_levels.id')
            ->get();
    }
}




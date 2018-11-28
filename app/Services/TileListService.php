<?php

namespace App\Services;

use Illuminate\Database\Query\Builder;

class TileListService
{

    public function get($userId)
    {
        return $this->getQuery($userId)->get();
    }

    protected function getQuery($userId): Builder
    {
        return \DB::query()
            ->selectRaw('
tile_types.display_name as tile_type,
tile_classes.display_name as tile_class,
mobilities.display_name as tile_mobility,
tech_levels.display_name as tech_level,

tiles.*,
chassis_armor_stats.armor,
chassis_armor_stats.move,
chassis.evasion,
chassis.cost
            ')
            ->from('tiles')
            ->join('chassis', 'chassis.id', '=', 'tiles.chassis_id')
            ->join('tile_types', 'tile_types.id', '=', 'chassis.tile_type_id')
            ->join('tile_classes', 'tile_classes.id', '=', 'chassis.tile_class_id')
            ->join('mobilities', 'mobilities.id', '=', 'chassis.mobility_id')
            ->join('tech_levels', 'tech_levels.id', '=', 'chassis.tech_level_id')
            ->join('chassis_armor_stats', 'chassis_armor_stats.chassis_id', '=', 'chassis.id')
            ->orderBy('tiles.updated_at', 'DESC')
            ->where('chassis_armor_stats.armor', '=', 'tiles.armor')
            ->where('tiles.user_id', $userId);
    }

}




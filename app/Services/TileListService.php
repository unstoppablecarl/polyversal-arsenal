<?php

namespace App\Services;

use App\Services\Exceptions\InvalidTileListSortableField;
use Illuminate\Database\Query\Builder;

class TileListService
{
    public const SORTABLE_COLUMNS = [
        'id',
        'tile_type',
        'tile_class',
        'tile_mobility',
        'tech_level',

        'armor',
        'move',
        'stealth',
        'evasion',

        'name',
        'targeting',
        'assault',
        'anti_missile_system_id',

        'cached_cost',
        'updated_at'
    ];

    public function get($userId)
    {
        return $this->getQuery($userId)->get();
    }

    public function getQuery(
        int $userId,
        string $sortField = null,
        string $sortDirection = null,
        string $tileNameSearch = null
    ): Builder
    {
        if ($sortField) {
            if (!in_array($sortField, static::SORTABLE_COLUMNS)) {
                throw new InvalidTileListSortableField($sortField);
            }
            $sortField = $this->prepareSortFiled($sortField);
        }

        $query = \DB::query()
            ->selectRaw('
tile_types.display_name as tile_type,
tile_classes.display_name as tile_class,
mobilities.display_name as tile_mobility,
tech_levels.display_name as tech_level,

assault_values.display_name as assault,
targeting_values.display_name as targeting,
tiles.*,
chassis_armor_stats.move,
chassis.evasion
            ')
            ->from('tiles')
            ->join('chassis', 'chassis.id', '=', 'tiles.chassis_id')
            ->join('tile_types', 'tile_types.id', '=', 'chassis.tile_type_id')
            ->join('tile_classes', 'tile_classes.id', '=', 'chassis.tile_class_id')
            ->join('mobilities', 'mobilities.id', '=', 'chassis.mobility_id')
            ->join('tech_levels', 'tech_levels.id', '=', 'chassis.tech_level_id')
            ->join('combat_values as assault_values', 'assault_values.id', '=', 'tiles.assault_id')
            ->join('combat_values as targeting_values', 'targeting_values.id', '=', 'tiles.targeting_id')
            ->join('chassis_armor_stats', function ($join) {
                $join->on('chassis_armor_stats.chassis_id', '=', 'chassis.id');
                $join->on('chassis_armor_stats.armor', '=', 'tiles.armor');
            })
            ->where('tiles.user_id', $userId);

        if ($sortField) {
            $query->orderBy($sortField, $sortDirection);
        }

        $query->orderBy('tiles.updated_at', 'DESC');

        if ($tileNameSearch) {
            $query->where('tiles.name', 'like', '%' . $tileNameSearch . '%');
        }

        return $query;
    }

    protected function prepareSortFiled(string $field)
    {
        $map = [
            'tech_level'    => 'tech_level_id',
            'tile_mobility' => 'mobility_id',
            'tile_class'    => 'tile_class_id',
            'tile_type'     => 'tile_type_id',
            'targeting'     => 'targeting_id',
            'assault'       => 'assault_id',
        ];

        return array_get($map, $field, $field);
    }
}




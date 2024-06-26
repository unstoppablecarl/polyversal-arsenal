<?php

namespace App\Services;

use App\Models\Ability;
use App\Models\Chassis;
use App\Models\Tile;
use App\Models\TilePrintSettings;
use App\Models\TileWeapon;
use App\Models\User;
use App\Services\Exceptions\InvalidAbilityTileTypeException;
use App\Services\Tile\TileImage;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class TileService
{
    /**
     * @var CostService
     */
    protected $costService;
    /**
     * @var TileImage
     */
    protected $tileImage;

    public function __construct(CostService $costService, TileImage $tileImage)
    {
        $this->costService = $costService;
        $this->tileImage   = $tileImage;
    }

    public function create(array $input, $userId): Tile
    {
        return DB::transaction(function () use ($input, $userId) {

            $chassis = $this->getChassis($input);

            $input['chassis_id'] = $chassis->id;
            $input['user_id']    = $userId;
            $abilityIds          = array_get($input, 'ability_ids', []);
            $tileWeapons         = array_get($input, 'tile_weapons', []);

            $input['cached_cost'] = 0;

            /** @var Tile $tile */
            $tile = Tile::query()->create($input);
            $this->syncAbilities($tile, $abilityIds);
            $this->syncTileWeapons($tile, $tileWeapons);

            $tile['cached_cost'] = $this->costService->total($tile);
            $tile->save();

            $tilePrintSettings = array_get($input, 'tile_print_settings', []);
            $tile->tilePrintSettings()->create($tilePrintSettings);

            return $tile;
        });
    }

    public function update(Tile $tile, array $input): Tile
    {
        return DB::transaction(function () use ($tile, $input) {

            $chassis = $this->getChassis($input);
            $tile->chassis()->associate($chassis);

            $tileInput = array_only($input, [
                'name',
                'targeting_id',
                'assault_id',
                'anti_missile_system_id',
                'armor',
                'stealth',
                'flavor_text',
            ]);

            $imageInput = $this->saveImageData($tile, $input);

            $tileInput = array_merge($tileInput, $imageInput);

            $tile->update($tileInput);
            $abilityIds  = array_get($input, 'ability_ids', []);
            $tileWeapons = array_get($input, 'tile_weapons', []);


            $this->syncAbilities($tile, $abilityIds);
            $this->syncTileWeapons($tile, $tileWeapons);

            $tile['cached_cost'] = $this->costService->total($tile);
            $tile->save();

            $tilePrintSettings = array_get($input, 'tile_print_settings', []);
            $this->updateTilePrintSettings($tile, $tilePrintSettings);

            unset(
                $tile['attributes'],
                $tile['chassis']
            );

            return $tile->fresh();
        });
    }

    public function delete(Tile $tile)
    {
        $tileImage = app(TileImage::class);

        $tileImage->deleteAllImages($tile);

        $tile->delete();
    }

    protected function syncTileWeapons(Tile $tile, array $tileWeapons)
    {
        unset($tile['tileWeapons']);
        $groups = collect($tileWeapons)
            ->mapToGroups(function ($tileWeapon) {
                $isNew = starts_with($tileWeapon['id'], 'new-');
                if ($isNew) {
                    return ['new' => $tileWeapon];
                } else {
                    return ['updated' => $tileWeapon];
                }
            });

        /** @var Collection $new */
        $newTileWeapons = array_get($groups, 'new', collect());

        /** @var Collection $updated */
        $updatedTileWeapons = array_get($groups, 'updated', collect());

        $this->updateTileWeapons($tile, $updatedTileWeapons);
        $this->createTileWeapons($tile, $newTileWeapons);

        unset($tile['tileWeapons']);
    }

    protected function updateTileWeapons(Tile $tile, $updatedTileWeapons): void
    {
        $updatedTileWeapons = $updatedTileWeapons->keyBy('id');
        $tile->tileWeapons->each(function ($currentTileWeapon) use ($tile, $updatedTileWeapons) {
            $id = $currentTileWeapon->id;

            $updated = $updatedTileWeapons->get($id);
            if ($updated) {
                $currentTileWeapon->fill($updated);
                $currentTileWeapon->save();
            } else {
                $currentTileWeapon->delete();
            }
        });
    }

    protected function createTileWeapons(Tile $tile, $newTileWeapons): void
    {
        $newTileWeapons->each(function ($tileWeaponRow) use ($tile) {
            $tileWeaponRow['tile_id'] = $tile->id;
            TileWeapon::query()->create($tileWeaponRow);
        });
    }

    protected function getChassis(array $input): Chassis
    {
        $where = array_only($input, [
            'tile_type_id',
            'tile_class_id',
            'tech_level_id',
            'mobility_id',
        ]);
        return Chassis::query()->where($where)->first();
    }

    protected function syncAbilities(Tile $tile, array $abilityIds)
    {
        $abilities = Ability::query()
            ->whereIn('id', $abilityIds)
            ->get();
        foreach ($abilities as $ability) {
            $tileTypeId = $tile->chassis->tile_type_id;
            if (!$ability->isValidTileType($tileTypeId)) {
                throw new InvalidAbilityTileTypeException($ability, $tileTypeId);
            }
        }

        $tile->abilities()->sync($abilityIds);
    }

    protected function saveImageData(Tile $tile, array $input): array
    {
        $tileInput = [];

        $frontImageData = array_get($input, 'new_front_image');
        if ($frontImageData) {
            $images    = $this->tileImage->saveFrontImage($tile, $frontImageData);
            $tileInput = array_merge($tileInput, $images);
        }

        $frontImageData = array_get($input, 'new_back_image');
        if ($frontImageData) {
            $images    = $this->tileImage->saveBackImage($tile, $frontImageData);
            $tileInput = array_merge($tileInput, $images);
        }

        $frontImageData = array_get($input, 'new_front_svg');
        if ($frontImageData) {
            $images    = $this->tileImage->saveFrontSvg($tile, $frontImageData);
            $tileInput = array_merge($tileInput, $images);
        }

        $frontImageData = array_get($input, 'new_back_svg');
        if ($frontImageData) {
            $images    = $this->tileImage->saveBackSvg($tile, $frontImageData);
            $tileInput = array_merge($tileInput, $images);
        }

        return $tileInput;
    }

    public function updateFrontSourceImage(Tile $tile, $data)
    {
        $tileInput = $this->tileImage->saveFrontSourceImage($tile, $data);
        $tile->update($tileInput);
        return $this->tileImage->url($tile->front_source_image);
    }

    public function updateBackSourceImage(Tile $tile, $data)
    {
        $tileInput = $this->tileImage->saveBackSourceImage($tile, $data);
        $tile->update($tileInput);
        return $this->tileImage->url($tile->back_source_image);
    }

    public function deleteFrontSourceImage(Tile $tile)
    {
        $this->tileImage->deleteFrontSourceImage($tile);
        $tile->update([
            'front_source_image' => null,
        ]);
    }

    public function deleteBackSourceImage(Tile $tile)
    {
        $this->tileImage->deleteBackSourceImage($tile);
        $tile->update([
            'back_source_image' => null,
        ]);
    }

    public function updateTilePrintSettings(Tile $tile, array $input): TilePrintSettings
    {
        $where = [
            'tile_id' => $tile->id,
        ];

        $tile->tilePrintSettings()->updateOrCreate($where, $input);

        return $tile->tilePrintSettings;
    }

    public function copy(Tile $sourceTile, User $newUser = null, string $name = null): Tile
    {
        return DB::transaction(function () use ($name, $sourceTile, $newUser) {

            $source = array_except($sourceTile->attributesToArray(), [
                'id',
                'created_at',
                'updated_at',

                'front_source_image',
                'back_source_image',
                'front_image',
                'front_thumb',
                'back_image',
                'back_thumb',
                'front_svg',
                'back_svg',
            ]);

            if ($newUser) {
                $source['user_id'] = $newUser->id;
            }
            if ($name) {
                $source['name'] = $name;
            }

            /** @var Tile $tile */
            $tile = Tile::query()->forceCreate($source);

            $abilityIds = $sourceTile->abilities()->pluck('abilities.id')->toArray();

            $this->syncAbilities($tile, $abilityIds);

            $sourceTile->tileWeapons->each(function (TileWeapon $sourceTileWeapon) use ($tile) {
                $attributes = array_except($sourceTileWeapon->attributesToArray(), [
                    'id',
                    'created_at',
                    'updated_at',
                ]);

                $attributes['tile_id'] = $tile->id;

                TileWeapon::query()->forceCreate($attributes);
            });

            $sourceTilePrintSettings = $sourceTile->tilePrintSettings->toArray();

            $tilePrintSettings            = array_except($sourceTilePrintSettings, [
                'id',
                'created_at',
                'updated_at',
            ]);
            $tilePrintSettings['tile_id'] = $tile->id;
            $tile->tilePrintSettings()->forceCreate($tilePrintSettings);
            return $tile;
        });
    }
}

<template>
    <div>
        <div class="form-group row header-row">
            <div class="col-sm-6 col-form-label">
                Stat
            </div>
            <div class="col-sm-1 col-form-label number-cell">
                Cost
            </div>
            <div class="col-sm-1 col-form-label number-cell">
                Move
            </div>
            <div class="col-sm-1 col-form-label number-cell">
                Evasion
            </div>
        </div>
        <div class="tab-content-divider"></div>

        <tile-classification-row
            v-model="tile_type_id"
        />

        <tile-class-row
            v-model="tile_class_id"
            :disabled="!hasTileClass"
        />

        <tile-stat-select
            title="Mobility"
            v-model="tile_mobility_id"
            :options="mobilityOptions"
        />

        <tile-stat-select
            title="Tech Level"
            v-model="tile_tech_level_id"
            :options="techLevelOptions"
        />

        <tile-stat-select
            title="Armor"
            v-model="tile_armor"
            :options="armorOptions"
            :show-evasion="false"
        />

        <tile-stat-select
            title="Targeting"
            v-model="tile_targeting_id"
            :options="targetingOptions"
            :show-move="false"
            :show-evasion="false"
        />

        <tile-stat-select
            title="Assault"
            v-model="tile_assault_id"
            :options="assaultOptions"
            :show-move="false"
            :show-evasion="false"
        />
        <div class="tab-content-divider-bottom"></div>

        <div class="row">
            <div class="col-sm-2"></div>
            <div class="col-sm-4 col-form-label number-cell">
                <strong>Total</strong>
            </div>
            <div class="col-sm-1 col-form-label number-cell">
                {{statsTotalCost}}
            </div>
            <div class="col-sm-1 col-form-label number-cell">
                {{move}}
            </div>
            <div class="col-sm-1 col-form-label number-cell">
                {{evasion}}
            </div>
        </div>

    </div>

</template>

<script>

    import Number from './functional/number';
    import TileStatSelect from './tabs-stats/tile-stat-select';
    import TileClassRow from './tabs-stats/tile-class-row';
    import TileClassificationRow from './tabs-stats/tile-classification-row';
    import {amaOptions, targetingOptions} from '../data/options';
    import {mapTileGetters, mapTileProperties} from '../data/mappers';

    export default {
        name: 'tabs-stats',
        components: {
            TileClassificationRow,
            TileClassRow, TileStatSelect, Number,
        },
        data() {
            return {
                amaOptions: amaOptions,
            };
        },
        computed: {
            ...mapTileGetters([
                'evasion',
                'move',
                'totalCost',
                'hasTileClass',
                'mobilityOptions',
                'techLevelOptions',
                'stealthOptions',
                'armorOptions',
                'targetingOptions',
                'statsTotalCost',
                'assaultOptions',
            ]),
            ...mapTileProperties({
                tile_type_id: 'tile_type_id',
                tile_class_id: 'tile_class_id',
                tile_armor: 'armor',
                tile_tech_level_id: 'tech_level_id',
                tile_mobility_id: 'mobility_id',
                tile_targeting_id: 'targeting_id',
                tile_assault_id: 'assault_id',
                tile_stealth: 'stealth',
                tile_ama_id: 'anti_missile_system_id',
                tile_name: 'name',
            }),
        },
    };

</script>

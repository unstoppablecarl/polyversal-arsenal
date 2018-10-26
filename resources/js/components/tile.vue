<template>
    <div class="tile">

        <tile-classification-row
            v-model="tile_type_id"
        />

        <tile-class-row
            v-model="tile_class"
            :disabled="!hasTileClass"
        />

        <tile-stat-select
            label="Mobility"
            v-model="tile_mobility_id"
            :options="mobilityOptions"
        />

        <tile-stat-select
            label="Tech Level"
            v-model="tile_tech_level_id"
            :options="techLevelOptions"
        />

        <tile-stat-select
            label="Armor"
            v-model="tile_armor"
            :options="armorOptions"
        />

        <tile-stat-select
            label="Targeting"
            v-model="tile_targeting_id"
            :options="targetingOptions"
        />

        <tile-stat-select
            label="Targeting"
            v-model="tile_assault_id"
            :options="assaultOptions"
        />

        <div class="row">
            <div class="col-sm-2"></div>
            <div class="col-sm-4 col-form-label number-cell">
                <strong>Total</strong>
            </div>
            <div class="col-sm-1 col-form-label number-cell">
                {{totalCost}}
            </div>
            <div class="col-sm-1 col-form-label number-cell">
                {{chassis.move}}
            </div>
            <div class="col-sm-1 col-form-label number-cell">
                {{chassis.evasion}}
            </div>
        </div>
    </div>
</template>

<script>
    import {
        ASSAULT_OPTIONS,
        TARGETING_OPTIONS,
        TILE_TYPE_ARMOR_VALUES,
        TILE_TYPE_CAVALRY_ID,
        TILE_TYPE_INFANTRY_ID,
        TILE_TYPE_OPTIONS, TILE_TYPE_VEHICLE_ID,
    } from '../data/constants';
    import WeaponGrid from './weapon-grid';
    import {mobilityOptions} from '../data/mobility';
    import {getChassis} from '../data/chassis';
    import {techLevelOptions} from '../data/tech-level';
    import Number from './number';
    import TileStatSelect from './tile-stat-select';
    import TileClassRow from './tile-class-row';
    import TileClassificationRow from './tile-classification-row';
    import {vehicleClasses} from '../data/class';

    export default {
        name: 'tile',
        components: {TileClassificationRow, TileClassRow, TileStatSelect, Number, WeaponGrid},
        props: {
            name: String,
        },
        data() {
            return {
                tile_type_id: TILE_TYPE_VEHICLE_ID,
                tile_class: 3,
                tile_armor: 3,
                tile_tech_level_id: 2,
                tile_mobility_id: 9,
                tile_targeting_id: 1,
                tile_assault_id: 1,
                tileTypeArmorValues: TILE_TYPE_ARMOR_VALUES,
                classifications: TILE_TYPE_OPTIONS,
                targetingOptions: TARGETING_OPTIONS,
                assaultOptions: ASSAULT_OPTIONS,

            };
        },
        computed: {
            chassis() {
                return getChassis(
                    this.tile_type_id,
                    this.tile_class,
                    this.tile_armor,
                    this.tile_tech_level_id,
                    this.tile_mobility_id,
                );
            },
            totalCost() {
                return this.chassis.cost + this.tile_armor + this.tile_targeting_id + this.tile_assault_id;
            },
            classOptions() {
                return calcOptions(vehicleClasses, (tile_armor) => {
                    return this.getZeroArmorChassisMod(tile_armor);
                });
            },
            armorOptions() {
                let armorOptions = this.tileTypeArmorValues[this.tile_type_id];

                armorOptions = armorOptions.map((id) => {
                    return 'Armor ' + id;
                });

                return calcOptions(armorOptions, (tile_armor) => {
                    return this.getZeroArmorChassisMod(tile_armor);
                });
            },
            techLevelOptions() {
                return calcOptions(techLevelOptions, (tile_tech_level_id) => {
                    return this.getTypicalChassisMod({tile_tech_level_id});
                });
            },
            mobilityOptions() {
                let options = mobilityOptions[this.tile_type_id];
                return calcOptions(options, (tile_mobility_id) => {
                    return this.getChassis({
                        tile_mobility_id,
                        tile_tech_level_id: 2,
                        tile_armor: 0,
                    });
                });
            },
            typicalChassis() {
                return this.getChassis({
                    tile_mobility_id: this.tile_mobility_id,
                    tile_tech_level_id: 2,
                });
            },
            hasTileClass() {
                return this.tile_type_id == TILE_TYPE_VEHICLE_ID;
            },

        },
        watch: {
            tile_type_id(val) {
                let armor       = this.tile_armor;
                // set armor to a value we know is valid
                this.tile_armor = 0;

                let firstKey          = Object.keys(this.mobilityOptions)[0];
                this.tile_mobility_id = this.mobilityOptions[firstKey].id;

                let max = this.getMaxArmorValue();

                if (armor > max) {
                    armor = max;
                }
                this.tile_armor = armor;

                if (!this.hasTileClass) {
                    this.tile_class = 1;
                }
            },
        },
        methods: {
            getMaxArmorValue() {
                let keys = Object.keys(this.armorOptions);
                let last = keys[keys.length - 1];
                return parseInt(last, 10);
            },
            getChassis(settings) {
                let {
                        tile_type_id,
                        tile_class,
                        tile_armor,
                        tile_tech_level_id,
                        tile_mobility_id,
                    } = this;

                settings = Object.assign({}, {
                    tile_type_id,
                    tile_class,
                    tile_armor,
                    tile_tech_level_id,
                    tile_mobility_id,
                }, settings);

                return getChassis(
                    settings.tile_type_id,
                    settings.tile_class,
                    settings.tile_armor,
                    settings.tile_tech_level_id,
                    settings.tile_mobility_id,
                );
            },
            getZeroArmorChassisMod(tile_armor) {
                let current = this.getChassis({
                    tile_armor: 0,
                });
                let result  = this.getChassis({tile_armor});
                let move    = result.move - current.move;
                let evasion = result.evasion - current.evasion;
                return {
                    id: result.id,
                    cost: tile_armor,
                    move: move,
                    evasion: evasion,
                };
            },
            getTypicalChassisMod(settings) {
                let typical = this.typicalChassis;
                let result  = this.getChassis(settings);
                let cost    = result.cost - typical.cost;
                let move    = result.move - typical.move;
                let evasion = result.evasion - typical.evasion;
                return {
                    id: result.id,
                    cost: cost,
                    move: move,
                    evasion: evasion,
                };
            },
        },
    };

    function calcOptions(options, getStats) {

        let out = [];
        Object.keys(options)
            .forEach((id) => {
                id                        = parseInt(id, 10);
                let {cost, move, evasion} = getStats(id);
                let label                 = options[id];
                out.push({
                    id,
                    label,
                    cost,
                    move,
                    evasion,
                });
            });
        return out;
    }

</script>

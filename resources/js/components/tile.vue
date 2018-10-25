<template>
    <div class="tile">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Classification</label>
            <div class="col-sm-4">
                <select class="form-control" v-model.number="tile_type_id">
                    <option v-for="(label, key) in classifications" v-bind:value="key">
                        {{label}}
                    </option>
                </select>
            </div>
        </div>

        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Class</label>
            <div class="col-sm-4">
                <select class="form-control" v-model.number="tile_class" :disabled="!hasTileClass">
                    <option v-for="(label, key) in classes" v-bind:value="key">
                        <template v-if="tile_type_id == 3">
                            Class {{key}}
                        </template>
                        {{label}}
                    </option>
                </select>
            </div>
            <div class="col-sm-1 col-form-label number-cell">
                <strong>Cost</strong>
            </div>
            <div class="col-sm-1 col-form-label number-cell">
                <strong>Move</strong>
            </div>
            <div class="col-sm-1 col-form-label number-cell">
                <strong>Evasion</strong>
            </div>
        </div>

        <tile-stat-select
        label="Mobility"
        v-model="tile_mobility_id"
        :selected_name="mobilityValueName"
        :options="mobilityOptions"
        :selected_cost="mobilityMods.cost"
        :selected_move="mobilityMods.move"
        :selected_evasion="mobilityMods.evasion"
        />

        <tile-stat-select
        label="Tech Level"
        v-model="tile_tech_level_id"
        :selected_name="techLevelValueName"
        :options="techLevelOptions"
        :selected_cost="techLevelChassisMods.cost"
        :selected_move="techLevelChassisMods.move"
        :selected_evasion="techLevelChassisMods.evasion"
        />

        <tile-stat-select
            label="Armor"
            v-model="tile_armor"
            :selected_name="armorValueName"
            :options="armorOptions"
            :selected_cost="armorMods.cost"
            :selected_move="armorMods.move"
            :selected_evasion="armorMods.evasion"
        />

        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Targeting</label>
            <div class="col-sm-4">
                <select class="form-control" v-model.number="tile_targeting_id">
                    <option v-for="(label, key) in dieOptions" v-bind:value="key">
                        {{label}}
                    </option>
                </select>
            </div>
            <div class="col-sm-1 col-form-label number-cell">
                <number :val="tile_targeting_id" :invert="true"/>
            </div>
        </div>

        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Assault</label>
            <div class="col-sm-4">
                <select class="form-control" v-model.number="tile_assault_id">
                    <option v-for="(label, key) in dieOptions" v-bind:value="key">
                        {{label}}
                    </option>
                </select>
            </div>
            <div class="col-sm-1 col-form-label number-cell">
                <number :val="tile_assault_id" :invert="true"/>
            </div>
        </div>

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
        TILE_TYPE_ARMOR_VALUES,
        TILE_TYPE_CAVALRY_ID,
        TILE_TYPE_INFANTRY_ID,
        TILE_TYPE_OPTIONS, TILE_TYPE_VEHICLE_ID,
    } from '../data/constants';
    import WeaponGrid from './weapon-grid';
    import {mobilityIdToDisplayName, mobilityOptions} from '../data/mobility';
    import {classOptions} from '../data/class';
    import {getChassis} from '../data/chassis';
    import {techLevelOptions} from '../data/tech-level';
    import Number from './number';
    import TileStatSelect from './tile-stat-select';

    export default {
        name: 'tile',
        components: {TileStatSelect, Number, WeaponGrid},
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
                dieOptions: {
                    1: 'D4',
                    2: 'D6',
                    3: 'D8',
                    4: 'D10',
                    5: 'D12',
                },
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
                    return this.getChassis({tile_mobility_id, tile_tech_level_id: 2});
                });
            },
            techLevelValueName() {
                return techLevelOptions[this.tile_tech_level_id];
            },
            mobilityValueName() {
                return mobilityIdToDisplayName[this.tile_mobility_id];
            },
            armorValueName() {
                return 'Armor ' + this.tile_armor;
            },
            typicalChassis() {
                return this.getChassis({
                    tile_mobility_id: this.tile_mobility_id,
                    tile_tech_level_id: 2,
                });
            },
            techLevelChassisMods() {
                return this.getTypicalChassisMod({
                    tile_tech_level_id: this.tile_tech_level_id,
                });
            },
            mobilityMods() {
                return this.getChassis({
                    tile_mobility_id: this.tile_mobility_id,
                    tile_tech_level_id: 2,
                    tile_armor: 0,
                });
            },
            armorMods() {
                return this.getZeroArmorChassisMod(this.tile_armor);
            },
            classes() {
                return classOptions[this.tile_type_id];
            },
            hasTileClass(){
                return this.tile_type_id == TILE_TYPE_VEHICLE_ID;
            }
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

                if(!this.hasTileClass){
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
                id = parseInt(id, 10)
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

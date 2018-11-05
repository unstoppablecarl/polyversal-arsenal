<template>
    <div class="tile">

        <h1>{{tile_name}}</h1>
        <h2>{{subTitle}}</h2>
        <tabs>
            <template slot="stats">

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

                <tile-name-row/>

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
                        {{chassis.move}}
                    </div>
                    <div class="col-sm-1 col-form-label number-cell">
                        {{chassis.evasion}}
                        <template v-if="tile_stealth">
                            (+{{tile_stealth}})
                        </template>
                    </div>
                </div>
            </template>
            <template slot="abilities">
                <div class="form-group row header-row">
                    <label class="col-sm-2 col-form-label">Ability</label>
                    <div class="col-sm-4">

                    </div>
                    <div class="col-sm-1 col-form-label number-cell">
                        Cost
                    </div>
                    <div class="col-sm col-form-label">
                        Notes
                    </div>

                </div>

                <div class="tab-content-divider"></div>

                <tile-ability-select
                    title="Stealth / Active Camo"
                    v-model="tile_stealth"
                    :options="stealthOptions"
                    :is-defensive="true"
                    :show-move="false"
                    :show-evasion="false"
                />

                <tile-ability-select
                    title="Anti-Missile Autoguns"
                    v-model="tile_ama_id"
                    :options="amaOptions"
                    :is-defensive="true"
                    :show-move="false"
                    :show-evasion="false"
                    :disabled="!hasAMAOption"
                />

                <tile-ability-list/>
            </template>

            <template slot="weapons">
                <weapon-grid
                    :scale="true"
                    :print-preview="false"
                />

                <weapon-grid-item-new/>
            </template>
        </tabs>

        <tile-print/>
    </div>

</template>

<script>

    import WeaponGrid from './weapon-grid';
    import Number from './number';
    import TileStatSelect from './tile-stat-select';
    import TileClassRow from './tile-class-row';
    import TileClassificationRow from './tile-classification-row';
    import TileDamageTrack from './tile-damage-track';
    import {amaOptions, assaultOptions, targetingOptions} from '../data/options';
    import {mapGetters} from 'vuex';
    import {mapTileProperties} from '../data/mappers';
    import TileAbilitySelect from './tile-ability-select';
    import {TILE_TYPE_VEHICLE_ID} from '../data/constants';
    import TileAbilityList from './tile-ability-list';
    import Tabs from './tabs';
    import WeaponGridItemNew from './weapon-grid-item-new';
    import TilePrint from './tile-print';
    import TileNameRow from './tile-name-row';

    export default {
        name: 'tile',
        components: {
            TileNameRow,
            TilePrint,
            WeaponGridItemNew,
            Tabs,
            TileAbilityList,
            TileAbilitySelect,
            TileDamageTrack, TileClassificationRow, TileClassRow, TileStatSelect, Number, WeaponGrid,
        },
        props: {
            name: String,
        },
        data() {
            return {
                targetingOptions: targetingOptions,
                assaultOptions: assaultOptions,
                amaOptions: amaOptions,
            };
        },
        computed: {
            ...mapGetters([
                'chassis',
                'totalCost',
                'hasTileClass',
                'mobilityOptions',
                'techLevelOptions',
                'stealthOptions',
                'armorOptions',
                'subTitle',
                'statsTotalCost'
            ]),

            ...mapTileProperties({
                tile_type_id: 'type_id',
                tile_class_id: 'class_id',
                tile_armor: 'armor',
                tile_tech_level_id: 'tech_level_id',
                tile_mobility_id: 'mobility_id',
                tile_targeting_id: 'targeting_id',
                tile_assault_id: 'assault_id',
                tile_stealth: 'stealth',
                tile_ama_id: 'ama_id',
                tile_name: 'name'
            }),
            hasAMAOption() {
                return this.tile_type_id == TILE_TYPE_VEHICLE_ID;
            },
        },
        watch: {},
        methods: {},
    };

</script>

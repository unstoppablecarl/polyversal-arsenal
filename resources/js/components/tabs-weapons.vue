<template>

    <div>
        <div class="form-group row header-row">
            <div class="col-sm-6 col-form-label">
                Weapons
            </div>

        </div>
        <div class="tab-content-divider"></div>
        <weapon-grid
            :scale="true"
        />

        <weapon-grid-item-new/>
        <table class="table table-bordered weapons-table">
            <thead>
            <tr>
                <th colspan="6"></th>
                <th class="col-cost" colspan="5">Cost Per Targeting Stat</th>
                <th class="col-controls"></th>
            </tr>
            <tr>
                <th class="col-name">Weapon</th>
                <th class="col-range">RNG</th>
                <th class="col-ap">AP</th>
                <th class="col-at">AT</th>
                <th class="col-aa">AA</th>
                <th class="col-damage">DMG</th>
                <th :class="['col-cost-heading', 'cost-left', ...costCssClasses('d4')]">
                    D4
                </th>
                <th :class="['col-cost-heading', ...costCssClasses('d6')]">
                    D6
                </th>
                <th :class="['col-cost-heading', ...costCssClasses('d8')]">
                    D8
                </th>
                <th :class="['col-cost-heading', ...costCssClasses('d10')]">
                    D10
                </th>
                <th :class="['col-cost-heading', 'cost-right', ...costCssClasses('d12')]">
                    D12
                </th>
            </tr>
            </thead>
            <tbody>
            <weapon-grid-item-select-new v-for="weapon in options" :key="weapon.id" v-bind="weapon"/>
            </tbody>
        </table>
    </div>
</template>

<script>

    import WeaponGrid from './weapon-grid';
    import WeaponGridItemNew from './weapon-grid/weapon-grid-item-new';
    import {
        TILE_WEAPON_TYPE_GROUND_ID,
        TILE_WEAPON_TYPE_ONLY_AA_ID,
        TILE_WEAPON_TYPE_WITH_AA_ID
    } from "../data/constants";
    import WeaponGridItemSelectNew from "./weapon-grid/weapon-grid-item-select-new";
    import {mapTileGetters, mapTileWeaponGetters} from "../data/mappers";

    export default {
        name: 'tabs-weapons',
        components: {
            WeaponGridItemSelectNew,
            WeaponGridItemNew,
            WeaponGrid,
        },
        data() {
            return {};
        },
        computed: {
            ...mapTileGetters([
                'isCurrentTargetingDie'
            ]),
            ...mapTileWeaponGetters([
                'options',
                'firstValidWeaponId'
            ]),
        },
        watch: {},
        methods: {
            costCssClasses(die) {
                let cssClass = die.toUpperCase() + '-bg';

                if (this.isCurrentTargetingDie(die)) {
                    cssClass += '-current';
                    return ['cost-current', cssClass]
                }

                return [cssClass];
            },
            addWeapon(tileWeaponTypeId) {
                let weapon = {
                    weapon_id: this.selectedWeaponId,
                    tile_weapon_type_id: tileWeaponTypeId,
                };

                this.$store.dispatch('tile_weapons/create', {weapon});
            },
            addWeaponGround() {
                this.addWeapon(TILE_WEAPON_TYPE_GROUND_ID);
            },
            addWeaponWithAA() {
                this.addWeapon(TILE_WEAPON_TYPE_WITH_AA_ID);
            },
            addWeaponOnlyAA() {
                this.addWeapon(TILE_WEAPON_TYPE_ONLY_AA_ID);
            },
        },
    };
</script>

<template>
    <div class="container weapon-grid-item-new">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Add Weapon</label>
            <div class="col-sm-4">
                <select class="form-control" v-model="selectedWeaponId">
                    <option v-for="item in options" v-bind:value="item.id">{{ item.display_name }}</option>
                </select>
            </div>
            <div class="col-sm-4">
                <button class="btn btn-primary" v-on:click="addWeaponGround">Add</button>
                <button class="btn btn-primary" v-on:click="addWeaponWithAA">Add With AA</button>
                <button class="btn btn-primary" v-on:click="addWeaponOnlyAA">Add Only AA</button>
            </div>
        </div>
    </div>
</template>

<script>
    import {
        TILE_WEAPON_TYPE_GROUND_ID,
        TILE_WEAPON_TYPE_ONLY_AA_ID,
        TILE_WEAPON_TYPE_WITH_AA_ID,
    } from '../data/constants';
    import {mapTileProperties, mapTileWeaponGetters} from '../data/mappers';

    export default {
        name: 'weapon-grid-item-new',
        data() {
            return {
                selectedWeaponId: null,
            };
        },
        created() {
            this.selectFirst();
        },
        watch: {
            tile_type_id() {
                this.selectFirst();
            },
        },
        methods: {
            addWeapon(tileWeaponTypeId) {
                let weapon = {
                    weapon_id: this.selectedWeaponId,
                    tile_weapon_type_id: tileWeaponTypeId,
                    arc: 'UP_90',
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
            selectFirst() {
                this.selectedWeaponId = this.firstValidWeaponId;
            },
        },
        computed: {
            ...mapTileProperties({
                tile_type_id: 'tile_type_id',
            }),
            ...mapTileWeaponGetters([
                'options',
                'firstValidWeaponId'
            ])
        },
    };
</script>

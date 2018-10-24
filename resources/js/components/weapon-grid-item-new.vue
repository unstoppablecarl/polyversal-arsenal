<template>
    <div class="container weapon-grid-item-new">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Add Weapon</label>
            <div class="col-sm-4">
                <select class="form-control" v-model="selectedWeapon">
                    <option v-for="item in weapons" v-bind:value="item.slug">
                        {{ item.name }}
                    </option>
                </select>
            </div>
            <div class="col-sm-4">
                <button class="btn btn-primary" v-on:click="addWeapon" :disabled="!selectedWeaponHasGround">Add</button>
                <button class="btn btn-primary" v-on:click="addWeaponWithAA"
                        :disabled="!selectedWeaponHasWithAA">Add With AA
                </button>
                <button class="btn btn-primary" v-on:click="addWeaponOnlyAA"
                        :disabled="!selectedWeaponHasOnlyAA">Add Only AA
                </button>
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

    export default {
        name: 'weapon-grid-item-new',
        props: {
            tile_type_id: Number,
            weaponsRepo: Object,
        },
        data() {
            return {
                selectedWeapon: null,
            };
        },
        mounted() {
            this.selectedWeapon = this.weaponsRepo.firstKey();
        },
        methods: {
            addWeapon(event) {
                this.$emit('add', this.selectedWeapon, TILE_WEAPON_TYPE_GROUND_ID);
            },
            addWeaponWithAA(event) {
                this.$emit('add', this.selectedWeapon, TILE_WEAPON_TYPE_WITH_AA_ID);
            },
            addWeaponOnlyAA(event) {
                this.$emit('add', this.selectedWeapon, TILE_WEAPON_TYPE_ONLY_AA_ID);
            },
        },
        computed: {
            weapons() {
                return this.weaponsRepo.all();
            },
            selectedWeaponHasGround() {
                return this.weaponsRepo.hasGround(this.selectedWeapon);
            },
            selectedWeaponHasWithAA() {
                return this.weaponsRepo.hasWithAA(this.selectedWeapon);
            },
            selectedWeaponHasOnlyAA() {
                return this.weaponsRepo.hasOnlyAA(this.selectedWeapon);
            },
        },
    };
</script>

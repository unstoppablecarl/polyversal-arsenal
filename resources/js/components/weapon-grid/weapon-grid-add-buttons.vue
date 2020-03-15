<template>
    <div>
        <button class="btn btn-primary" v-on:click="addWeaponGround"><i class="fa fa-plus"></i></button>
        <button class="btn btn-primary" v-on:click="addWeaponWithAA"><i class="fa fa-plus"></i> With AA</button>
        <button class="btn btn-primary" v-on:click="addWeaponOnlyAA"><i class="fa fa-plus"></i> Only AA</button>
    </div>
</template>

<script>
    import {
        TILE_WEAPON_TYPE_GROUND_ID,
        TILE_WEAPON_TYPE_ONLY_AA_ID,
        TILE_WEAPON_TYPE_WITH_AA_ID,
    } from '../../data/constants';

    export default {
        name: 'weapon-grid-add-buttons',
        props: {
            weaponId: null,
        },
        methods: {
            addWeapon(tileWeaponTypeId) {
                this.$store.dispatch('tile_weapons/create', {
                    weaponId: this.weaponId,
                    tileWeaponTypeId
                });
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

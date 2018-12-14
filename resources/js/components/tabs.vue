<template>
    <ul class="nav nav-tabs">
        <li class="nav-item nav-item-info">
            <router-link :to="{name: 'tile-info', params: {id}}" :class="['nav-link', {active: selectedInfo}]">
                Info
                <span class="badge badge-x">
                        -
                    </span>
            </router-link>
        </li>
        <li class="nav-item">
            <router-link :to="{name: 'tile-stats', params: {id}}" :class="['nav-link', {active: selectedStats}]">
                Stats
                <span class="badge">
                        {{statsTotalCost}}
                    </span>
            </router-link>
        </li>
        <li class="nav-item">
            <router-link :to="{name: 'tile-abilities', params: {id}}"
                         :class="['nav-link', {active: selectedAbilities}]">
                Abilities
                <span class="badge">
                        {{abilitiesTotalCost}}
                    </span>
            </router-link>
        </li>
        <li class="nav-item">
            <router-link :to="{name: 'tile-weapons', params: {id}}" :class="['nav-link', {active: selectedWeapons}]">
                Weapons
                <span class="badge">
                        {{weaponsTotalCost}}
                    </span>
            </router-link>
        </li>
        <li class="nav-item">
            <span class="nav-link disabled">
                Total Cost
                <strong class="text-danger">
                    {{totalCost}}
                </strong>
            </span>
        </li>
    </ul>


</template>

<script>
    import {mapGetters} from 'vuex';
    import {mapAbilityGetters, mapTileGetters, mapTileProperties, mapTileWeaponGetters} from '../data/mappers';

    export default {
        name: 'tabs',
        computed: {
            ...mapGetters([
                'totalCost',
            ]),
            ...mapTileGetters([
                'statsTotalCost',
            ]),
            ...mapTileWeaponGetters({
                weaponsTotalCost: 'totalCost',
            }),
            ...mapAbilityGetters({
                abilitiesTotalCost: 'totalCost',
            }),
            ...mapTileProperties({
                tile_id: 'id',
            }),
            id() {
                return this.tile_id || 'create';
            },
            selectedInfo() {
                return this.$route.name == 'tile-info';
            },
            selectedStats() {
                return this.$route.name == 'tile-stats';
            },
            selectedAbilities() {
                return this.$route.name == 'tile-abilities';
            },
            selectedWeapons() {
                return this.$route.name == 'tile-weapons';
            },
        },
    };

</script>

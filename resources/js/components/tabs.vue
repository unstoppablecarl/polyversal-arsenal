<template>
    <div class="tabs-container">

        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a href="#" :class="['nav-link', {active: selectedStats}]" @click.prevent="clickStats">
                    Stats
                    <span class="badge">
                        {{statsTotalCost}}
                    </span>
                </a>
            </li>
            <li class="nav-item">
                <a href="#" :class="['nav-link', {active: selectedAbilities}]" @click.prevent="clickAbilities">
                    Abilities
                    <span class="badge">
                        {{abilitiesTotalCost}}
                    </span>
                </a>
            </li>
            <li class="nav-item">
                <a href="#" :class="['nav-link', {active: selectedWeapons}]" @click.prevent="clickWeapons">

                    Weapons
                    <span class="badge">
                        {{weaponsTotalCost}}
                    </span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link disabled" href="#">
                    Total Cost
                    <strong class="text-danger">
                        {{totalCost}}
                    </strong>
                </a>
            </li>
        </ul>

        <div class="tab-content" v-if="selectedStats">
            <slot name="stats">
                stats
            </slot>
        </div>

        <div class="tab-content" v-if="selectedAbilities">
            <slot name="abilities">
                abilities
            </slot>
        </div>

        <div class="tab-content" v-if="selectedWeapons">
            <slot name="weapons">
                weapons
            </slot>
        </div>

    </div>
</template>

<script>

    import {mapGetters} from 'vuex';

    const STATS     = 'stats';
    const ABILITIES = 'abilities';
    const WEAPONS   = 'weapons';

    export default {
        name: 'tabs',
        props: {},
        data() {
            return {
                selected: 'stats',
            };
        },
        computed: {
            ...mapGetters([
                'totalCost',
                'statsTotalCost',
                'abilitiesTotalCost',
                'weaponsTotalCost',
            ]),
            selectedStats() {
                return this.selected === STATS;
            },
            selectedAbilities() {
                return this.selected === ABILITIES;
            },
            selectedWeapons() {
                return this.selected === WEAPONS;
            },
        },
        methods: {
            clickStats() {
                return this.selected = STATS;
            },
            clickAbilities() {
                return this.selected = ABILITIES;
            },
            clickWeapons() {
                return this.selected = WEAPONS;
            },
        },
    };

</script>

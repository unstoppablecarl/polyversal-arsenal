<template>
    <div class="tile-print">

        <h1 class="text-center">{{tile_name}}</h1>
        <h2 class="text-center">{{printSubTitle}}</h2>
        <div class="row">
            <div class="col-sm-3"></div>
            <div class="col-sm-3">
                <p>
                    <strong>Move</strong> {{move}}
                </p>
                <p>
                    <strong>Targeting</strong> {{targeting}}
                </p>
                <p>
                    <strong>Assault</strong> {{assault}}
                </p>
                <p>
                    <strong>Evasion</strong> {{evasion}}
                </p>

                <p v-if="tile_stealth">
                    <strong>Stealth / Active Camo</strong> {{tile_stealth}}
                </p>
            </div>
            <div class="col-sm-3">

                <h4>Abilities</h4>
                <p v-for="ability in abilityList">{{ability}}</p>
            </div>
        </div>



        <div class="controls">
            <div class="btn-group btn-group-sm">
                <button class="btn btn-dark" disabled>
                    scale
                </button>
                <button class="btn btn-primary" @click="(scale = false)" :disabled="!scale">
                    &times; 1
                </button>
                <button class="btn btn-primary" @click="(scale = true)" :disabled="scale">
                    &times; 2
                </button>
            </div>
        </div>
        <div :class="[{'scale-2': scale}]">

            <weapon-grid
            :scale="scale"
            :print-preview="true"
            />

            <tile-damage-track/>
        </div>
    </div>
</template>

<script>

    import WeaponGrid from './weapon-grid';
    import TileDamageTrack from './tile-damage-track';
    import {mapGetters} from 'vuex';
    import {mapAbilityGetters, mapTileGetters, mapTileProperties} from '../data/mappers';
    import {targetingById} from '../data/options';

    export default {
        name: 'tile-print',
        components: {TileDamageTrack, WeaponGrid},
        props: {},
        data() {
            return {
                scale: true,
            };
        },
        computed: {
            ...mapTileGetters([
                'printSubTitle',
                'evasion',
                'move',
            ]),
            ...mapAbilityGetters([
'abilityList'
            ]),
            targeting() {
                return targetingById[this.tile_targeting_id].display_name;
            },
            assault() {
                return targetingById[this.tile_assault_id].display_name;
            },
            ...mapTileProperties({
                tile_type_id: 'tile_type_id',
                tile_class_id: 'class_id',
                tile_armor: 'armor',
                tile_tech_level_id: 'tech_level_id',
                tile_mobility_id: 'mobility_id',
                tile_targeting_id: 'targeting_id',
                tile_assault_id: 'assault_id',
                tile_stealth: 'stealth',
                tile_ama_id: 'ama_id',
                tile_name: 'name',
            }),
        },
        methods: {},
    };

</script>

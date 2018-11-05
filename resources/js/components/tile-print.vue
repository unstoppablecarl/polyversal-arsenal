<template>
    <div class="tile-print">

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

        <h1>{{tile_name}}</h1>
        <h2>{{subTitle}}</h2>
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
            <strong>Stealth</strong> {{tile_stealth}}
        </p>

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
    import {mapTileProperties} from '../data/mappers';
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
            ...mapGetters([
                'subTitle',
                'chassis',
            ]),
            ...mapTileProperties({
                tile_stealth: 'stealth',
            }),
            targeting() {
                return targetingById[this.tile_targeting_id].display_name;
            },
            assault() {
                return targetingById[this.tile_assault_id].display_name;
            },
            evasion() {
                return this.chassis.evasion;
            },
            move() {
                return this.chassis.move;
            },
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
                tile_name: 'name',
            }),
        },
        methods: {},
    };

</script>

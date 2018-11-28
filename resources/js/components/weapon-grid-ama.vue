<template>
    <div class="weapon-grid-item" v-if="hasAMA">
        <div class="col-move"></div>
        <div class="col-name">

            <img src="/img/icon-ama.svg" class="icon-ama"/>
            Anti-Missile
        </div>

        <div class="col-quantity">
            1
        </div>
        <div class="col-arc">
            <img src="/img/arc-360.svg" class="arc arc-x">
        </div>
        <div class="col-arc-direction">

        </div>
        <div class="col-arc-size">

        </div>
        <div class="col-range">12</div>
        <div :class="['col-ap', 'NONE-bg']">-</div>
        <div :class="['col-at', 'NONE-bg']">-</div>
        <div :class="['col-aa', aaDisplay + '-bg']">{{aaDisplay}}</div>
        <div class="col-damage">-</div>
        <div class="col-tile-weapon-type">

        </div>
        <div class="col-cost"></div>
        <div class="col-controls">

        </div>
    </div>
</template>

<script>
    import {amaById} from '../data/options';
    import {mapTileProperties} from '../data/mappers';
    import {AMA_NONE_ID} from '../data/constants';

    export default {
        name: 'weapon-grid-ama',
        props: {},
        data() {
            return {};
        },
        computed: {
            ...mapTileProperties({
                tile_ama_id: 'anti_missile_system_id',
            }),
            hasAMA() {
                return this.tile_ama_id !== AMA_NONE_ID;
            },
            aaDisplay() {
                if (this.tile_ama_id) {
                    return amaById[this.tile_ama_id].display_name;
                }
            },
        },
        watch: {},
        methods: {},
    };

</script>

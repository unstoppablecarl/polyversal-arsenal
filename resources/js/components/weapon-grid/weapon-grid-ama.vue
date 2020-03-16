<template>
    <tr class="weapon-grid-item" v-if="hasAMA">
        <td class="col-move"></td>
        <td class="col-name">

            <img src="/img/icon-ama.svg" class="icon-ama"/>
            Anti-Missile
        </td>

        <td class="col-quantity">
            1
        </td>
        <td class="col-arc">
            <img src="/img/arc-360.svg" class="arc arc-x">
        </td>
        <td class="col-arc-direction">

        </td>
        <td class="col-arc-size">

        </td>
        <td class="col-range">12</td>
        <td :class="['col-ap', 'NONE-bg']">-</td>
        <td :class="['col-at', 'NONE-bg']">-</td>
        <td :class="['col-aa', aaDisplay + '-bg']">{{aaDisplay}}</td>
        <td class="col-damage">-</td>
        <td class="col-tile-weapon-type"></td>
        <td class="col-cost"></td>
        <td class="col-total"></td>
        <td class="col-controls"></td>
    </tr>
</template>

<script>
    import {amaById} from '../../data/options';
    import {mapTileProperties} from '../../data/mappers';
    import {AMA_NONE_ID} from '../../data/constants';

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

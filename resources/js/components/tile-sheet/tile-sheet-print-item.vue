<template>
    <div :class="`tile-sheet-print-item slot-${tileSlotIndex}`">
        <tile-front-svg v-if="side === 'front'"/>
        <tile-back-svg v-else/>
    </div>
</template>
<script>

import {tileSlotStores} from '../../store/tile-sheet-slot-instances';
import TileFrontSvg from '../tile-print/tile-front-svg';
import TileBackSvg from '../tile-print/tile-back-svg';

export default {
    name: 'tile-sheet-print-item',
    components: {
        TileBackSvg,
        TileFrontSvg,
    },
    props: {
        tileSlotIndex: {
            required: true
        },
        tileId: {
            required: true
        },
        side: {
            required: true
        }
    },
    beforeCreate() {
        let tileSlotIndex = this.$options.propsData.tileSlotIndex;
        this.$store       = tileSlotStores[tileSlotIndex];
    }
}
</script>

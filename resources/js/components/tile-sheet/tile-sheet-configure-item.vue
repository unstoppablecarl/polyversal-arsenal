<template>
    <div>
        <div class="row">
            <div class="col-sm-6">
                <tile-front-print-card-body v-if="side === 'front'"/>
                <tile-back-print-card-body v-else/>
            </div>
            <div class="col-sm-6">
                <tile-front-svg v-if="side === 'front'"/>
                <tile-back-svg v-else/>
            </div>
        </div>
    </div>
</template>

<script>

import TileFrontSvg from '../tile-print/tile-front-svg';
import TileBackSvg from '../tile-print/tile-back-svg';
import TileFrontPrintCardBody from '../tile-print/tile-front-print-card-body';
import TileBackPrintCardBody from '../tile-print/tile-back-print-card-body';
import {tileSlotStores} from '../../store/tile-sheet-slot-instances'

export default {
    name: 'tile-sheet-configure-item',
    components: {
        TileBackPrintCardBody,
        TileFrontPrintCardBody,
        TileBackSvg,
        TileFrontSvg,
    },
    beforeCreate() {
        let tileSlotIndex = this.$options.propsData.tileSlotIndex;
        this.$store       = tileSlotStores[tileSlotIndex];
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
    }
};

</script>

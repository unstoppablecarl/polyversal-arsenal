<template>
    <div class="tile-sheet-page">
        <template v-for="({tile, side}, index) in tileSlots">
            <tile-sheet-print-item
                :tileId="tile.id"
                :side="side"
                :tileSlotIndex="index"
            />
        </template>
    </div>
</template>
<script>

import {mapGetters} from 'vuex';
import TileSheetPrintItem from './tile-sheet-print-item';

export default {
    name: 'tile-sheet-print',
    components: {TileSheetPrintItem},
    data() {
        return {};
    },
    computed: {
        ...mapGetters([
            'tileSlots',
            'tileSlotsEmpty',
            'generating'
        ]),
        disabled() {
            return this.tileSlotsEmpty || this.generating
        }
    }
}
</script>

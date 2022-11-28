<template>
    <div class="tile-sheet-create">

        <div class="container no-print">
            <div class="row">
                <div class="col-sm-12">
                    <h4>
                        Your Tiles
                    </h4>
                </div>
            </div>
        </div>

        <tile-sheet-grid/>

        <hr class="no-print">

        <div class="container no-print">
            <div class="row">
                <div class="col-sm-12">
                    <h4>
                        Tile Sheet Slots
                    </h4>
                </div>
            </div>
        </div>

        <tile-sheet-configure/>

        <tile-sheet-print/>
    </div>
</template>

<script>

import TileSheetGrid from './tile-sheet/tile-sheet-grid';
import TileFrontPrintCard from './tile-print/tile-front-print-card';
import {mapGetters} from 'vuex';
import TileSheetConfigureItem from './tile-sheet/tile-sheet-configure-item';
import TileSheetPrint from './tile-sheet/tile-sheet-print';
import TileSheetConfigure from './tile-sheet/tile-sheet-configure';

export default {
    name: 'app-tile-sheet-create',
    components: {
        TileSheetConfigure,
        TileSheetPrint,
        TileSheetConfigureItem,
        TileSheetGrid,
        TileFrontPrintCard
    },
    data() {
        return {
            iframeLoaded: false
        }
    },
    methods: {
        setFront(index) {
            this.$store.dispatch('setSide', {index, side: 'front'})
        },
        setBack(index) {
            this.$store.dispatch('setSide', {index, side: 'back'})
        },
        removeIndex(index) {
            this.$store.dispatch('deleteIndex', index)
        },
        onIframeLoad: function () {
            if (!this.pdfBase64) {
                return
            }
            this.iframeLoaded = true;
        }
    },
    computed: {
        ...mapGetters([
            'tileSlots',
            'generating',
            'pdfBase64'
        ])
    },
    watch: {
        generating(newValue, prevValue) {
            if (newValue && !prevValue) {
                this.iframeLoaded = false;
            }
        }
    }
};

</script>

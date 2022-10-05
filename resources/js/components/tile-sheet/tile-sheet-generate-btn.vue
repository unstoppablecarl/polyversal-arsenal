<template>

    <button class="btn btn-primary btn-lg" :disabled="disabled" @click="print">

        <template v-if="generating">
            Generating Tile Sheet
            <i class="fas fa-spin fa-cog"></i>
        </template>
        <template v-else>
            Generate Tile Sheet
        </template>
    </button>

</template>
<script>

import {mapGetters} from 'vuex';
import {makePdf} from '../../lib/tile-sheet-pdf'

export default {
    name: 'tile-sheet-print-btn',
    data() {
        return {};
    },
    methods: {
        print() {
            this.$store.dispatch('generate')
        }
    },
    computed: {
        ...mapGetters([
            'tileSlotsEmpty',
            'generating'
        ]),
        disabled() {
            return this.tileSlotsEmpty || this.generating
        }
    }
}
</script>

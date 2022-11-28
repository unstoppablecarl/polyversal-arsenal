<template>
    <div>
        <input v-model="manualOffsetX"/>
        <input v-model="margin"/>
        <button class="btn btn-primary btn-lg" :disabled="disabled" @click="print">


            <template v-if="generating">
                Generating Tile Sheet
                <i class="fas fa-spin fa-cog"></i>
            </template>
            <template v-else>
                Generate Tile Sheet
            </template>
        </button>
    </div>
</template>
<script>

import {mapGetters} from 'vuex';

export default {
    name: 'tile-sheet-generate-btn',
    data() {
        return {
            manualOffsetX: -0.4,
            margin: 0.25
        };
    },
    methods: {
        print() {
            this.$store.dispatch('generate', {
                manualOffsetX: parseFloat(this.manualOffsetX),
                margin: parseFloat(this.margin)
            })
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

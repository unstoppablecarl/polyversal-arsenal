<template>
    <tile-grid
        :columns="columns"
    >
        <template v-slot:after-headers>
            <th> Add to Sheet</th>
        </template>
        <template v-slot:after-cells="{row}">
            <td>
                <div class="btn-group btn-group-sm">
                    <span class="btn btn-light btn-group-sm">
                        Front
                    </span>
                    <button class="btn btn-light btn-group-sm"
                            @click="remove({tile:row, side:'front'})"
                    >
                        &minus;
                    </button>
                    <button class="btn btn-light btn-group-sm"
                            @click="add({tile:row, side:'front'})"
                            :disabled="tileSlotsFull">
                        &plus;
                    </button>
                    <span class="btn btn-secondary btn-group-sm">
                        {{ getFrontCounts(row) }}
                    </span>
                </div>

                <div class="btn-group btn-group-sm">
                    <span class="btn btn-light btn-group-sm">
                        Back
                    </span>
                    <button class="btn btn-light btn-group-sm"
                            @click="remove({tile:row, side:'back'})"
                    >
                        &minus;
                    </button>
                    <button class="btn btn-light btn-group-sm"
                            @click="add({tile:row, side:'back'})"
                            :disabled="tileSlotsFull">
                        &plus;
                    </button>
                    <span class="btn btn-secondary btn-group-sm">
                        {{ getBackCounts(row) }}
                    </span>
                </div>
            </td>

        </template>

    </tile-grid>
</template>

<script>

import TileGrid from '../tile-grid/tile-grid';
import {mapGetters} from 'vuex';

export default {
    name: 'app-tile-sheet-grid',
    components: {TileGrid},
    data() {
        return {

            columns: [
                {
                    field: 'id',
                    label: 'ID',
                },
                {
                    field: 'name',
                    label: 'Name',
                },
                {
                    field: 'tile_type',
                    label: 'Type',
                },
                {
                    field: 'tile_class',
                    label: 'Class',
                },
                {
                    field: 'tile_mobility',
                    label: 'Mobility',
                },
                {
                    field: 'tech_level',
                    label: 'Tech Level',
                },
                {
                    field: 'cached_cost',
                    label: 'Cost',
                    type: 'number',
                },
            ],
        };
    },
    methods: {
        add({tile, side}) {
            this.$store.dispatch('add', {tile, side})
        },
        getFrontCounts(tile) {
            return this.$store.getters.getCounts(tile).front || null
        },
        getBackCounts(tile) {
            return this.$store.getters.getCounts(tile).back || null
        },
        getCounts(tile) {
            return this.$store.getters.getCounts(tile)
        },
        addedToSheet(tile) {
            return this.$store.getters.addedToSheet(tile)
        },
        remove({tile, side}) {
            if (!this.getFrontCounts(tile)) {
                return
            }
            this.$store.dispatch('delete', {tile, side})
        }
    },
    computed: {
        ...mapGetters([
            'tileSlotsFull'
        ])
    }
}
</script>

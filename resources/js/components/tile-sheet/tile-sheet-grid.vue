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
                    <span class="btn btn-light btn-group-sm btn-cosmetic disabled">
                        Front
                    </span>
                    <button class="btn btn-light btn-group-sm"
                            @click="remove({tileId: row.id, side:'front'})"
                            :disabled="!getFrontAddedToSheet(row.id)"
                    >
                        &minus;
                    </button>
                    <button class="btn btn-light btn-group-sm"
                            @click="add({tileId: row.id, side:'front'})"
                            :disabled="tileSlotsFull || adding">
                        <template v-if="adding">
                            <i class="fas fa-spin fa-cog"></i>
                        </template>
                        <template v-else>
                            &plus;
                        </template>
                    </button>
                    <span class="btn btn-secondary btn-group-sm btn-cosmetic disabled">

                        {{ getFrontCounts(row.id) }}
                    </span>
                </div>

                <div class="btn-group btn-group-sm">
                    <span class="btn btn-light btn-group-sm btn-cosmetic disabled">
                        Back
                    </span>
                    <button class="btn btn-light btn-group-sm"
                            @click="remove({tileId: row.id, side:'back'})"
                            :disabled="!getBackAddedToSheet(row.id)"
                    >
                        &minus;
                    </button>
                    <button class="btn btn-light btn-group-sm"
                            @click="add({tileId: row.id, side:'back'})"
                            :disabled="tileSlotsFull || adding">
                        <template v-if="adding">
                            <i class="fas fa-spin fa-cog"></i>
                        </template>
                        <template v-else>
                            &plus;
                        </template>
                    </button>
                    <span class="btn btn-secondary btn-group-sm btn-cosmetic disabled">
                        {{ getBackCounts(row.id) }}
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

            adding: false
        };
    },
    methods: {
        add({tileId, side}) {
            this.adding = true

            this.$store.dispatch('add', {tileId, side})
                .then(() => {
                    this.adding = false
                })
        },
        getFrontCounts(tileId) {
            return this.$store.getters.getCounts(tileId).front || null
        },
        getBackCounts(tileId) {
            return this.$store.getters.getCounts(tileId).back || null
        },
        remove({tileId, side}) {
            this.$store.dispatch('delete', {tileId, side})
        }
    },
    computed: {
        ...mapGetters([
            'tileSlotsFull',
            'addedToSheet',
            'getFrontAddedToSheet',
            'getBackAddedToSheet'
        ]),
    }
}
</script>

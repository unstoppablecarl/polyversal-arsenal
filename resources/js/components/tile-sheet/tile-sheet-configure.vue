<template>

    <div class="container-fluid no-print">

        <table class="table table-striped table-bordered">
            <thead>
            <tr>
                <th>Slot</th>
                <th>Id</th>
                <th>Name</th>
                <th>Preview</th>
                <th></th>
            </tr>
            </thead>

            <tbody>
            <tr v-for="({tile, side}, index) in tileSlots">
                <td>{{ index + 1 }}</td>
                <td>{{ tile.id }}</td>
                <td>
                    {{ tile.name }}
                </td>
                <td>

                    <tile-sheet-configure-item
                        :tileId="tile.id"
                        :side="side"
                        :tileSlotIndex="index"
                    />
                </td>
                <td>
                    <div class="btn-group btn-group-sm">
                        <button class="btn btn-light btn-group-sm"
                                :disabled="side === 'front'"
                                @click="setFront(index)"
                        >
                            Front
                        </button>
                        <button class="btn btn-light btn-group-sm"
                                :disabled="side === 'back'"
                                @click="setBack(index)"
                        >
                            Back
                        </button>
                    </div>

                    <button class="btn btn-danger btn-sm"
                            @click="removeIndex(index)"
                    >
                        <i class="fa fa-trash-can"></i>
                    </button>
                </td>
            </tr>
            </tbody>
        </table>
    </div>

</template>

<script>

import {mapGetters} from 'vuex';
import TileSheetConfigureItem from './tile-sheet-configure-item';

export default {
    name: 'tile-sheet-configure',
    components: {
        TileSheetConfigureItem,
    },
    data() {
        return {}
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
    },
    computed: {
        ...mapGetters([
            'tileSlots',
        ])
    },
};

</script>

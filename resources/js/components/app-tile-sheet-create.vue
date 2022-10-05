<template>
    <div class="tile-sheet-create">

        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <h4>
                        Your Tiles
                    </h4>
                </div>
            </div>
        </div>

        <tile-sheet-grid/>

        <hr>
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <h4>
                        Tile Sheet Slots
                    </h4>
                </div>
            </div>
        </div>

        <div class="container-fluid">

            <table class="table table-striped table-bordered">
                <thead>
                <tr>
                    <th>Slot</th>
                    <th>Id</th>
                    <th>Name</th>
                    <th>Preview</th>
                    <th>Side</th>
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

                        <template v-if="side === 'front'">
                            <img :src="tile.front_thumb_url"/>
                        </template>
                        <template v-else>
                            <img :src="tile.back_thumb_url"/>
                        </template>
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

                        <button class="btn btn-danger btn-group-sm"
                                @click="removeIndex(index)"
                        >
                            <i class="fa fa-trash-can"></i>
                        </button>
                    </td>
                </tr>
                </tbody>
            </table>

            <div class="container">
                <div class="row">
                    <div class="col-sm-12 text-center">
                        <tile-sheet-print-btn/>

                        <hr/>
                        <iframe width="100%" height="1000"
                                :src="pdfBase64"
                                @load="onIframeLoad"
                                v-show="iframeLoaded"
                        />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

import TileSheetGrid from './tile-sheet/tile-sheet-grid';
import TileSheetPrintBtn from './tile-sheet/tile-sheet-generate-btn';
import TileFrontPrintCard from './tile-print/tile-front-print-card';
import {mapGetters} from 'vuex';

export default {
    name: 'app-tile-sheet-create',
    components: {
        TileSheetGrid,
        TileSheetPrintBtn,
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

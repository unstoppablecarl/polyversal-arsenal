<template>
    <div>

        <h4>Abilities</h4>
        <div v-for="ability in abilityList">{{ability}}</div>


        <div class="tile-print">
            <div class="card">
                <div class="card-header">
                    <div class="float-right">
                        <button class="btn btn-light" @click="toggleFrontCutLine">
                            <template v-if="showFrontCutLine">
                                Hide
                            </template>
                            <template v-else>
                                Show
                            </template>
                            Cut Line
                        </button>

                        <button class="btn btn-light" @click="saveFrontPng">Save PNG</button>
                        <button class="btn btn-light" @click="saveFrontSvg">Save SVG</button>
                    </div>
                    <h3 class="card-title">Tile Front</h3>
                </div>

                <div class="card-body">

                    <tile-front-svg
                        :show-cut-line="showFrontCutLine"/>
                </div>
            </div>
            <br>
            <div class="card">
                <div class="card-header">
                    <div class="float-right">
                        <button class="btn btn-light" @click="toggleBackCutLine">
                            <template v-if="showBackCutLine">
                                Hide
                            </template>
                            <template v-else>
                                Show
                            </template>
                            Cut Line
                        </button>

                        <button class="btn btn-light" @click="saveBackPng">Save PNG</button>
                        <button class="btn btn-light" @click="saveBackSvg">Save SVG</button>
                    </div>
                    <h3 class="card-title">Tile Back</h3>
                </div>

                <div class="card-body">

                </div>
            </div>
        </div>
        <br>
    </div>
</template>

<script>

    import WeaponGrid from './weapon-grid';
    import TileDamageTrack from './tile-damage-track';
    import {mapAbilityGetters} from '../data/mappers';
    import TileFrontSvg from './tile-print/tile-front-svg';
    import svgToPngBase64 from '../lib/svg-to-png-base64';
    import svgToBase64 from '../lib/svg-to-base64';
    import downloadDataURL from '../lib/download-data-url';

    export default {
        name: 'tile-print',
        components: {TileFrontSvg, TileDamageTrack, WeaponGrid},
        props: {},
        data() {
            return {
                scale: true,
                showFrontCutLine: false,
                showBackCutLine: false,

            };
        },
        computed: {
            ...mapAbilityGetters([
                'abilityList',
            ]),

        },
        methods: {
            toggleFrontCutLine() {
                this.showFrontCutLine = !this.showFrontCutLine;
            },
            toggleBackCutLine(){

            },
            saveFrontSvg() {

                this.$store.dispatch('images/loadFrontSourceImageBase64FromUrl')
                    .then(() => {
                        let fileName = 'polyversal-front-' + this.fileName + '.svg';
                        let base64   = svgToBase64('tile-front-svg');

                        downloadDataURL(base64, fileName);
                    });
            },
            saveBackSvg() {

            },
            saveFrontPng() {

                this.$store.dispatch('images/loadFrontSourceImageBase64FromUrl')
                    .then(() => {

                        let inchHeight = 3.25;
                        let inchWidth  = 3.71;

                        let pxWidth  = inchWidth * 300;
                        let pxHeight = inchHeight * 300;

                        let fileName = 'polyversal-front-' + this.fileName + '.png';
                        svgToPngBase64('tile-front-svg', pxWidth, pxHeight)
                            .then((base64) => {
                                downloadDataURL(base64, fileName);
                            });

                    });
            },
            saveBackPng(){

            }
        },
    };

</script>

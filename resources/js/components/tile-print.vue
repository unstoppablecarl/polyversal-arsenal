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
                        :show-cut-line="showFrontCutLine"
                    />
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
                    <tile-back-svg
                        :show-cut-line="showFrontCutLine"
                    />
                </div>
            </div>
        </div>
        <br>
    </div>
</template>

<script>

    import WeaponGrid from './weapon-grid';
    import TileDamageTrack from './tile-damage-track';
    import {mapAbilityGetters, mapImageGetters} from '../data/mappers';
    import TileFrontSvg from './tile-print/tile-front-svg';
    import downloadDataURL from '../lib/download-data-url';
    import TileBackSvg from './tile-print/tile-back-svg';

    export default {
        name: 'tile-print',
        components: {TileBackSvg, TileFrontSvg, TileDamageTrack, WeaponGrid},
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
            ...mapImageGetters([
                'frontFileName',
                'backFileName'
            ])
        },
        methods: {
            toggleFrontCutLine() {
                this.showFrontCutLine = !this.showFrontCutLine;
            },
            toggleBackCutLine() {
                this.showBackCutLine = !this.showBackCutLine;
            },
            saveFrontSvg() {
                this.$store.dispatch('images/getFrontSvgBase64')
                    .then((base64) => {
                        let fileName = this.frontFileName + '.svg';
                        downloadDataURL(base64, fileName);
                    });
            },
            saveBackSvg() {
                this.$store.dispatch('images/getBackSvgBase64')
                    .then((base64) => {
                        let fileName = this.backFileName + '.svg';
                        downloadDataURL(base64, fileName);
                    });
            },
            saveFrontPng() {

                this.$store.dispatch('images/getFrontImageBase64')
                    .then((base64) => {
                        let fileName = this.frontFileName + '.png';
                        downloadDataURL(base64, fileName);
                    });

            },
            saveBackPng() {
                this.$store.dispatch('images/getBackImageBase64')
                    .then((base64) => {
                        let fileName = this.backFileName + '.png';
                        downloadDataURL(base64, fileName);
                    });
            },
        },
    };

</script>

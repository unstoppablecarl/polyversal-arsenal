<template>
    <div>

        <h4>Abilities</h4>
        <div v-for="ability in abilityList">{{ability}}</div>

        <div class="tile-print">

            <tile-print-card
                title="Tile Front"
                id-prefix="tile-front"
                @savePng="saveFrontPng"
                @saveSvg="saveFrontSvg"
            >
                <template slot-scope="{showCutLine, printerFriendly, cutLineColor}">
                    <tile-front-svg
                        :show-cut-line="showCutLine"
                        :printer-friendly="printerFriendly"
                        :cut-line-color="cutLineColor"
                    />
                </template>
            </tile-print-card>

            <br>

            <tile-print-card
                title="Tile Back"
                id-prefix="tile-back"
                @savePng="saveBackPng"
                @saveSvg="saveBackSvg"
            >
                <template slot-scope="{showCutLine, printerFriendly, cutLineColor}">
                    <tile-back-svg
                        :show-cut-line="showCutLine"
                        :printer-friendly="printerFriendly"
                        :cut-line-color="cutLineColor"
                    />
                </template>
            </tile-print-card>
        </div>
        <br>
    </div>
</template>

<script>

    import WeaponGrid from './weapon-grid';
    import {mapAbilityGetters, mapImageGetters} from '../data/mappers';
    import TileFrontSvg from './tile-print/tile-front-svg';
    import downloadDataURL from '../lib/download-data-url';
    import TileBackSvg from './tile-print/tile-back-svg';
    import TilePrintCard from './tile-print/tile-print-card';

    export default {
        name: 'tile-print',
        components: {TilePrintCard, TileBackSvg, TileFrontSvg, WeaponGrid},
        props: {},
        data() {
            return {
                scale: true,
                showFrontCutLine: false,
                frontCutLineColor: 'red',
                frontPrinterFriendly: false,
                showBackCutLine: false,
            };
        },
        computed: {
            ...mapAbilityGetters([
                'abilityList',
            ]),
            ...mapImageGetters([
                'frontFileName',
                'backFileName',
            ]),
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

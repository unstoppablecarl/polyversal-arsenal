<template>
    <div>
        <h4>Abilities</h4>
        <div class="tile-print">

            <tile-print-card
                storeNamespace="images/front"
                title="Tile Front"
                id-prefix="tile-front"
            >
                <template>
                    <tile-front-svg/>
                </template>
            </tile-print-card>

            <br>

            <tile-print-card
                storeNamespace="images/back"
                title="Tile Back"
                id-prefix="tile-back"
            >
                <template>
                    <tile-back-svg/>
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
            };
        },
        computed: {
            ...mapImageGetters({
                frontFileName: 'front/fileName',
                backFileName: 'back/fileName',
            }),
        },
        methods: {
            //saveFrontSvg() {
            //    this.$store.dispatch('images/front/getSvgBase64')
            //        .then((base64) => {
            //            let fileName = this.frontFileName + '.svg';
            //            downloadDataURL(base64, fileName);
            //        });
            //},
            //saveBackSvg() {
            //    this.$store.dispatch('images/back/getSvgBase64')
            //        .then((base64) => {
            //            let fileName = this.backFileName + '.svg';
            //            downloadDataURL(base64, fileName);
            //        });
            //},
            //saveFrontPng() {
            //    this.$store.dispatch('images/front/getImageBase64')
            //        .then((base64) => {
            //            let fileName = this.frontFileName + '.png';
            //            downloadDataURL(base64, fileName);
            //        });
            //},
            //saveBackPng() {
            //    this.$store.dispatch('images/back/getImageBase64')
            //        .then((base64) => {
            //            let fileName = this.backFileName + '.png';
            //            downloadDataURL(base64, fileName);
            //        });
            //},
        },
    };

</script>

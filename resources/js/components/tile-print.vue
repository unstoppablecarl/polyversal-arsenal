<template>
    <div>

        <h4>Abilities</h4>
        <div v-for="ability in abilityList">{{ability}}</div>
        <button class="btn btn-light" @click="toggleCutLine">
            <template v-if="showCutLine">
                Hide
            </template>
            <template v-else>
                Show
            </template>

            Cut Line
        </button>
        <button class="btn btn-light" @click="savePng">Save PNG</button>
        <button class="btn btn-light" @click="saveSvg">Save SVG</button>

        <div class="tile-print">

            <tile-svg
                :show-cut-line="showCutLine"/>
        </div>

        <br>
        <br>
        <br>
        <br>


    </div>
</template>

<script>

    import WeaponGrid from './weapon-grid';
    import TileDamageTrack from './tile-damage-track';
    import {mapAbilityGetters, mapTileGetters, mapTileProperties} from '../data/mappers';
    import {targetingById} from '../data/options';
    import TileSvg from './tile-print/tile-svg';
    import svgToPngBase64 from '../lib/svg-to-png-base64';
    import svgToBase64 from '../lib/svg-to-base64';
    import downloadDataURL from '../lib/download-data-url';

    export default {
        name: 'tile-print',
        components: {TileSvg, TileDamageTrack, WeaponGrid},
        props: {},
        data() {
            return {
                scale: true,
                showCutLine: false,
            };
        },
        computed: {
            ...mapTileGetters([
                'printSubTitle',
                'evasion',
                'move',
                'fileName',
                'imageBase64',
            ]),
            ...mapAbilityGetters([
                'abilityList',
            ]),
            targeting() {
                return targetingById[this.tile_targeting_id].display_name;
            },
            assault() {
                return targetingById[this.tile_assault_id].display_name;
            },
            assaultIconCssClass() {
                return 'icon-' + targetingById[this.tile_assault_id].name;
            },
            targetingIconCssClass() {
                return 'icon-' + targetingById[this.tile_targeting_id].name;
            },
            assaultNumber() {
                return this.assault.split('D')[1];
            },
            targetingNumber() {
                return this.targeting.split('D')[1];
            },
            ...mapTileProperties({
                tile_type_id: 'tile_type_id',
                tile_class_id: 'class_id',
                tile_armor: 'armor',
                tile_tech_level_id: 'tech_level_id',
                tile_mobility_id: 'mobility_id',
                tile_targeting_id: 'targeting_id',
                tile_assault_id: 'assault_id',
                tile_stealth: 'stealth',
                tile_ama_id: 'ama_id',
                tile_name: 'name',
                tile_front_image_url: 'front_image_url',
            }),
            bgStyle() {
                return {
                    'background-image': `url('${this.tile_front_image_url}')`,
                };
            },
        },
        methods: {
            toggleCutLine() {
                this.showCutLine = !this.showCutLine;
            },
            saveSvg() {

                this.$store.dispatch('tile/loadImageBase64')
                    .then(() => {
                        let fileName = 'polyversal-' + this.fileName + '.svg';

                        let base64 = svgToBase64('tile-svg');

                        downloadDataURL(base64, fileName);
                    });
            },
            savePng() {

                this.$store.dispatch('tile/loadImageBase64')
                    .then(() => {

                        let inchHeight = 3.25;
                        let inchWidth  = 3.71;

                        let pxWidth  = inchWidth * 300;
                        let pxHeight = inchHeight * 300;

                        let fileName = 'polyversal-' + this.fileName + '.png';
                        svgToPngBase64('tile-svg', pxWidth, pxHeight)
                            .then((base64) => {

                                downloadDataURL(base64, fileName);
                            });

                    });
            },
        },
    };

</script>

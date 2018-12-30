<template>

    <svg
        id="tile-back-svg"
        class="tile-svg"
        viewBox="0 0 268 232"
        :width="(268.3 * 2)"
        :height="(232.3 * 2)"
        xmlns="http://www.w3.org/2000/svg"
        xmlns:svg="http://www.w3.org/2000/svg"
    >
        <defs>
            <svg:style type="text/css" v-html="svgCss"></svg:style>
            <filter id="desaturate">
                <feColorMatrix type="saturate"
                               values="0.1"/>
            </filter>
        </defs>

        <clipPath id="tile-clip">
            <polygon points="67.4,231.8 0.6,116.2 67.4,0.5 200.9,0.5 267.7,116.2 200.9,231.8 "/>
        </clipPath>

        <g :class="{'printer-friendly': printerFriendly}">

            <image
                v-if="backSourceImageUrl"
                :href="backSourceImageUrl"
                width="100%"
                height="100%"
                clip-path="url(#tile-clip)"
                filter="url(#desaturate)"
                class="bg-image"
            />

            <polygon points="71.8,224.2 9.4,116.2 71.8,8.2 196.5,8.2 258.8,116.2 196.5,224.2 "
                     :stroke="cutLineColor"
                     stroke-width="0.25"
                     fill="none"
                     stroke-miterlimit="10"
                     v-if="showCutLine"
            />

            <text :x="268.3 * 0.5" y="20" class="title">{{tile_name}}</text>
            <text :x="268.3 * 0.5" y="32" class="subtitle">{{printSubTitle}}</text>

            <text :x="56" y="50.25" class="flavor-text">
                <tspan v-for="(row, index) in flavorTextArray" :x="56" :y="50.25 + (index + 1) * 10">{{row}}</tspan>
            </text>

            <g class="cost-header-box" :transform.once="costBoxTransform">
                <rect
                    x="0"
                    y="0"
                    :width.once="col1Width"
                    height="8"
                />
                <rect
                    :x.once="col1Width"
                    y="0"
                    :width.once="col2Width"
                    height="8"
                />
                <rect
                    :x="col1Width + col2Width"
                    y="0"
                    :width.once="col3Width"
                    height="8"
                />
            </g>

            <g :transform.once="costBoxTransform">
                <text :x="2" y="4.75" class="cost-header-left">PSYCH PROFILE</text>
                <text :x="col1Width + col2Width * 0.5" y="4.75" class="cost-header-center">MOD</text>
                <text :x="col1Width + col2Width + col3Width * 0.5" y="4.75" class="cost-header-center">COST</text>
            </g>

            <g class="cost-row-box" :transform.once="costBoxTransform">

                <g v-for="i in [0,1,2,3,4,5,6,7]">

                    <rect x="0"
                          :y.once="8 + i * 8"
                          :width.once="col1Width"
                          height="8"
                    />

                    <rect
                        :x.once="col1Width"
                        :y.once="8 + i * 8"
                        :width.once="col2Width"
                        height="8"
                    />

                    <rect
                        :x.once="col1Width + col2Width"
                        :y.once="8 + i * 8"
                        :width.once="col3Width"
                        height="8"
                    />
                </g>
            </g>

            <g :transform.once="costBoxTextTransform">
                <g v-for="(item, index) in psychProfiles">

                    <text :x="2" :y="(index + 1) * 8" class="cost-row-left">{{item.label}}</text>
                    <template v-if="index == 0">
                        <text :x="col1Width + col2Width * 0.5" :y="(index + 1) * 8"
                              class="cost-row-center">{{item.percent_label}}
                        </text>
                    </template>
                    <template v-else>
                        <text :x="col1Width + col2Width - 2" :y="(index + 1) * 8"
                              class="cost-row-right">{{item.percent_label}}
                        </text>

                    </template>
                    <text :x="col1Width + col2Width + col3Width - 2" :y="(index + 1) * 8"
                          class="cost-row-right">{{item.cost}}
                    </text>
                </g>
            </g>


            <polygon class="shape-fill" points="71.9,201.2 67.6,193.7 71.9,186.2 80.6,186.2 84.9,193.7 80.6,201.2 "/>

            <g transform="translate(76.233, 193.699)">
                <text class="shape-header-value max-size" y="-13">
                    MAX SIZE
                </text>
                <text class="shape-value" y="0.75">
                    {{maxSize}}
                </text>
            </g>
            <g transform="translate(190.552, 193.699)">
                <circle class="shape-fill" cx="0" cy="0" r="7.48"/>
                <text class="shape-header-value" y="-13">
                    COST
                </text>
                <text class="shape-value" y="0.75">
                    {{totalCost}}
                </text>
            </g>
        </g>
    </svg>

</template>

<script>

    import {
        mapImageGetters,
        mapTileGetters,
        mapTileProperties,
    } from '../../data/mappers';
    import getTileSvgCss from '../../lib/get-tile-svg-css';
    import textWrap from 'svg-text-wrap';
    import {mapGetters} from 'vuex';
    import psychProfiles from '../../data/psych-profiles';

    export default {
        name: 'tile-back-svg',
        components: {},
        props: {
            showCutLine: {
                //default: false,
            },
            cutLineColor: {
                default: 'red',
            },
            printerFriendly: {
                //default: true,
            },
        },
        data() {
            return {
                svgCss: null,
                costBoxTransform: 'translate(92.65, 133.198)',
                costBoxTextTransform: 'translate(92.65, ' + (133.198 + 4.75) + ')',

                col1Width: 44.25,
                col2Width: 19.38,
                col3Width: 19.38,
            };
        },
        mounted() {
            this.svgCss = getTileSvgCss('tile-back-svg-css');
        },
        computed: {
            ...mapGetters([
                'totalCost',
            ]),
            ...mapTileGetters([
                'printSubTitle',
                'maxSize',
            ]),
            ...mapImageGetters([
                'backSourceImageUrl',
            ]),
            ...mapTileProperties({
                tile_name: 'name',
                tile_back_image_url: 'back_image_url',
                flavor_text: 'flavor_text',
            }),
            flavorTextArray() {
                if(this.flavor_text){
                    return textWrap(this.flavor_text + '', 200);
                }
                return '';
            },
            psychProfiles() {
                return psychProfiles.map(item => {
                    let percent_label = item.percent_label;
                    let percent       = item.percent;
                    let label         = item.label;

                    let cost = this.totalCost + (percent * this.totalCost);
                    cost     = Math.ceil(cost);

                    return {
                        label,
                        percent_label,
                        percent,
                        cost,
                    };
                });
            },
        },
    };

</script>

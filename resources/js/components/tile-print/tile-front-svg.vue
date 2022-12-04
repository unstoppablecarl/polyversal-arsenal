<template>
    <svg
        id="tile-front-svg"
        class="tile-svg"
        viewBox="0 0 268 232"
        :width="(268.3 * 2)"
        :height="(232.3 * 2)"
        xmlns="http://www.w3.org/2000/svg"
        xmlns:svg="http://www.w3.org/2000/svg"
    >
        <defs>
            <svg:style type="text/css" v-html="svgCss"></svg:style>

            <g :id="`damage-defense-${tile_id}`" transform="translate(-5, 0)">
                <polygon points="3.3,1.5 2.5,2.2 2.5,5.1 5,6.5 7.5,5.1 7.5,2.2 6.7,1.5 "/>
            </g>

            <g id="damage-destroyed" transform="translate(-5, 0)">
                <path d="M7.1,5l0-2.5L5,1.2L2.9,2.5l0,2.5l0.9,0.5v1.1L4,6.8l0.3-0.1l0-1.2l0.3-0.1l0.3,0.1l0,1.2L5,6.8l0.3-0.1l0-1.2
		l0.3-0.1l0.3,0.1v1.2L6,6.8l0.3-0.1V5.5L7.1,5z M4,4.8L3.4,4.5l0-0.7L4,3.5l0.6,0.3l0,0.7L4,4.8z M5.4,4.5l0-0.7L6,3.5l0.6,0.3
		l0,0.7L6,4.8L5.4,4.5z"/>
                <path d="M7.1,5l0-2.5L5,1.2L2.9,2.5l0,2.5l0.9,0.5v1.1L4,6.8l0.3-0.1l0-1.2l0.3-0.1l0.3,0.1l0,1.2L5,6.8l0.3-0.1
			l0-1.2l0.3-0.1l0.3,0.1v1.2L6,6.8l0.3-0.1V5.5L7.1,5z M4,4.8L3.4,4.5l0-0.7L4,3.5l0.6,0.3l0,0.7L4,4.8z M5.4,4.5l0-0.7L6,3.5
			l0.6,0.3l0,0.7L6,4.8L5.4,4.5z"/>
            </g>

            <g id="damage-fuel-leak" transform="translate(-5, 0)">
                <path d="M6.1,3.7C5.7,3,5.4,2.4,5.1,1.8L5,1.5L4.9,1.8C4.6,2.4,4.3,3,3.9,3.7C3.6,4.1,3.1,5.2,3.9,6
	C4.2,6.3,4.6,6.5,5,6.5c0.4,0,0.8-0.2,1.1-0.5C6.9,5.2,6.4,4.1,6.1,3.7z"/>
            </g>

            <g id="damage-hull-breach" transform="translate(-5, 0)">
                <path d="M7.6,4.4H5l1.6-2.9h1V4.4z M2.4,4.4H5L3.4,1.5h-1V4.4z M5.4,5.4C5.2,5.1,5.1,4.8,5,4.6C4.9,4.8,4.8,5.1,4.6,5.4
	C4.5,5.6,4.3,6,4.6,6.3C4.7,6.4,4.8,6.5,5,6.5c0.2,0,0.3-0.1,0.4-0.2C5.7,6,5.5,5.6,5.4,5.4z"/>
            </g>

            <g id="damage-immobilized" transform="translate(-5, 0)">
                <polygon points="6,1.5 4,1.5 2.5,3 2.5,5 4,6.5 6,6.5 7.5,5 7.5,3 "/>
            </g>

            <g id="damage-jump" transform="translate(-5, 0)">
                <path d="M6.2,4.9l-0.7,1L5.5,5.2L5,6.5v0v0l0,0v0L4.5,5.2L4.5,5.9l-0.7-1C4.1,4.9,4.6,5,5,5C5.4,5,5.9,4.9,6.2,4.9z
	 M6.6,1.5H3.4L2.8,3.1v1.1C3.4,4.3,4.2,4.4,5,4.4c0.8,0,1.6-0.1,2.2-0.3V3.1L6.6,1.5z"/>
            </g>

            <g id="damage-stress" transform="translate(-5, 0)">
                <polygon points="7.5,4 5,3.2 5,1.5 2.5,4 5,4.8 5,6.5 "/>
            </g>

            <g id="damage-targeting" transform="translate(-5, 0)">
                <path
                    d="M3,3H2.5V1.5H4V2H3V3z M4,6H3V5H2.5v1.5H4V6z M7.5,1.5H6V2h1v1h0.5V1.5z M7.5,5H7v1H6v0.5h1.5V5z"/>
            </g>

            <g id="damage-weapon" transform="translate(-5, 0)">
                <polygon points="7.5,1.5 5,3.2 2.5,1.5 4.2,4 2.5,6.5 5,4.8 7.5,6.5 5.8,4 "/>
            </g>
        </defs>
        <clipPath :id="`${prefix}-front-tile-clip`">
            <polygon points="67.4,231.8 0.6,116.2 67.4,0.5 200.9,0.5 267.7,116.2 200.9,231.8 "/>
        </clipPath>

        <g id="tile-settings" :class="{'printer-friendly': frontIsPrinterFriendly}">
            <rect x="0" y="0" width="268" height="232" fill="#fff" :clip-path="`url(#${prefix}-front-tile-clip)`"/>
            <image
                v-if="sourceImageUrl"
                :href="sourceImageUrl"
                width="100%"
                height="100%"
                :clip-path="`url(#${prefix}-front-tile-clip)`"
                class="bg-image"
            />

            <polygon points="71.8,224.2 9.4,116.2 71.8,8.2 196.5,8.2 258.8,116.2 196.5,224.2 "
                     v-if="frontCutLineColor"
                     :stroke="frontCutLineColor"
                     stroke-width="0.25"
                     fill="none"
                     stroke-miterlimit="10"
                     class="cut-line"
            />
            <template v-if="stealth">
                <g id="stealth">
                    <polygon id="stealth-bg" class="fill-gray"
                             points="50.5,143.9 44.1,147.6 37.8,143.9 37.8,136.6 44.1,133 50.5,136.6 	"/>
                    <path id="evasion-icon_1_" class="icon-white" d="M41.2,132.3l-0.4-0.7l3.4-1.9l3.4,1.9l-0.4,0.7H41.2z M38.7,133.7l-0.4-0.7l-3.4,1.9v3.9
		h0.8L38.7,133.7z M52.5,138.9h0.8V135L50,133l-0.4,0.7L52.5,138.9z M49.5,146.8l0.4,0.7l3.4-1.9v-3.9h-0.8L49.5,146.8z M35.8,141.7
		h-0.8v3.9l3.4,1.9l0.4-0.7L35.8,141.7z M41.2,148.2l-0.4,0.7l3.4,1.9l3.4-1.9l-0.4-0.7H41.2z"/>
                    <text id="stealth-number" x="44.1" y="140.8" class="icon-number">
                        {{ stealth }}
                    </text>
                </g>
            </template>
            <g id="evasion">
                <polygon id="evasion-bg" class="fill-gray"
                         points="37.8,120.6 30.2,125 22.6,120.6 22.6,111.8 30.2,107.4 37.8,111.8 	"/>
                <path id="evasion-icon" class="icon-white" d="M27.3,108.2l-0.4-0.7l3.4-1.9l3.4,1.9l-0.4,0.7H27.3z M24.8,109.6l-0.4-0.7l-3.4,1.9v3.9
		h0.8L24.8,109.6z M38.6,114.7h0.8v-3.9l-3.4-1.9l-0.4,0.7L38.6,114.7z M35.6,122.7l0.4,0.7l3.4-1.9v-3.9h-0.8L35.6,122.7z
		 M21.8,117.6H21v3.9l3.4,1.9l0.4-0.7L21.8,117.6z M27.3,124.1l-0.4,0.7l3.4,1.9l3.4-1.9l-0.4-0.7H27.3z"/>
                <text id="evasion-number" x="30.3" y="116.65" class="icon-number">
                    {{ evasion }}
                </text>
            </g>
            <g id="assault">
                <polygon id="assault-bg" :class="assaultIconCssClass"
                         points="44.1,83.3 36.5,87.7 36.5,96.5 44.1,100.8 51.7,96.5 51.7,87.7 	"/>
                <path id="assault-icon" class="icon-white" d="M47.5,83.7l5.5,3.2v1.8l-0.8,0v-1.3l-4.7-2.7l0-0.3L47.5,83.7z M41.6,81.1l0,3.3l2.5,1.5
		l2.5-1.5l0-3.3l-2.5,1.5L41.6,81.1z M53.6,92.1l1.5-2.5l-3.3,0l-1.5,2.5l1.5,2.5l3.3,0L53.6,92.1z M46.7,103l0-3.3l-2.5-1.5
		l-2.5,1.5l0,3.3l2.5-1.5L46.7,103z M52.2,95.4v1.3l-4.7,2.7l0,0.3l0,0.7l5.5-3.2v-1.8L52.2,95.4z M36.1,88.7v-1.3l4.7-2.7l0-0.3
		l0-0.7l-5.5,3.2v1.8L36.1,88.7z M40.7,99.7l0-0.3l-4.7-2.7v-1.3l-0.9,0v1.8l5.5,3.2L40.7,99.7z M38,92.1l-1.5-2.5l-3.3,0l1.5,2.5
		l-1.5,2.5l3.3,0L38,92.1z"/>
                <text id="assault-number" x="44.1" y="92.6" class="icon-number">
                    {{ assaultNumber }}
                </text>

            </g>
            <g id="targeting">
                <polygon id="targeting-bg" :class="targetingIconCssClass"
                         points="58.1,59.2 50.4,63.6 50.4,72.3 58.1,76.7 65.7,72.3 65.7,63.6 	"/>
                <path id="targeting-icon" class="icon-white" d="M48.8,66.3l4.7,1.6L50,69.2l0,0l-0.9,0.3l0,0l-0.3,0.1l-2.2-1.6L48.8,66.3z M56.4,77.2
		l1.6,2.2l1.6-2.2l-1.6-4.7L56.4,77.2z M67.3,66.3L67,66.5l0,0l-4.3,1.5l3.5,1.2l0,0l0.8,0.3l0,0l0.3,0.1l2.2-1.6L67.3,66.3z
		 M59.4,59.4L59.4,59.4l0.2-0.7l-0.2-0.2h0l-1.4-2l-1.4,2l0,0l-0.2,0.2l1.6,4.7L59.4,59.4z M60.2,59.9l5.9,3.4v2.5l0.8-0.3v-2.7
		l-6.5-3.8L60.2,59.9z M55.9,76L50,72.6v-2.5l-0.9,0.3v2.7l6.5,3.8L55.9,76z M66.1,70.1v2.5L60.2,76l0.3,0.8l6.5-3.8v-2.7L66.1,70.1
		z M50,65.8v-2.5l5.9-3.4l-0.3-0.8l-6.5,3.8v2.7L50,65.8z"/>
                <text id="targeting-number" x="58" y="68.5" class="icon-number">
                    {{ targetingNumber }}
                </text>

            </g>
            <g id="move">
                <polygon id="move-bg" class="fill-gray"
                         points="72,36.8 67.6,39.4 65,43.8 67.6,48.2 72,50.8 76.4,48.2 79,43.8 79,43.8 76.4,39.4 	"/>
                <path id="move-icon" class="icon-white" d="M72,56.8l-3.5-6.1v-0.8l3.5,2l3.5-2v0.8L72,56.8z M72,30.9l3.5,6.1v0.8l-3.5-2l-3.5,2V37
		L72,30.9z M59,43.8l6.1-3.5H66l-2,3.5l2,3.5h-0.8L59,43.8z M84.9,43.8l-6.1,3.5H78l2-3.5l-2-3.5h0.8L84.9,43.8z"/>
                <text id="move-number" x="71.973" y="45" class="icon-number icon-number-move">
                    {{ move }}
                </text>
            </g>

            <text :x="268.3 * 0.5" y="20" :class="['title', {'text-invert': frontInvertTitle}]">
                <tspan v-for="(row, index) in tileNameArray" :x="268.3 * 0.5" :y="20 + (index) * nameLineHeight"
                       v-html="row"></tspan>
            </text>
            <text :x="268.3 * 0.5" :y="32 + ((tileNameArray.length - 1) * nameLineHeight)"
                  :class="['subtitle', {'text-invert': frontInvertTitle}]">{{ printSubTitle }}
            </text>

            <tile-weapon-grid-svg/>

            <g :transform="'translate(0, 196)'">
                <tile-svg-damage-track/>
            </g>
        </g>
    </svg>

</template>

<script>

import {mapFrontImageGetters, mapTileGetters, mapTileProperties,} from '../../data/mappers';
import {targetingById} from '../../data/options';
import TileSvgDamageTrack from './tile-svg-damge-track';
import TileWeaponGridSvg from './tile-weapon-grid-svg';
import getTileSvgCss from '../../lib/get-tile-svg-css';
import {mapGetters} from 'vuex';
import textWrap from 'svg-text-wrap';

let prefix = 0;

export default {
    name: 'tile-front-svg',
    components: {TileWeaponGridSvg, TileSvgDamageTrack},
    props: {
        cutLineColor: null,
    },
    data() {
        prefix++
        return {
            prefix,
            svgCss: null,
            nameLineHeight: 12,
        };
    },
    mounted() {
        this.svgCss = getTileSvgCss('tile-front-svg-css');
    },
    computed: {
        ...mapTileGetters([
            'printSubTitle',
            'evasion',
            'move',
        ]),
        tileNameArray() {
            if (this.tile_name) {

                let result = [];
                this.tile_name.split('\n')
                    .map((str) => {
                        return textWrap(str + '', 140);
                    })
                    .forEach((arr) => {
                        result = result.concat(arr)
                    })

                return result;
            }
            return '';
        },
        ...mapFrontImageGetters([
            'sourceImageUrl',
        ]),
        ...mapTileProperties({
            tile_id: 'id',
            tile_targeting_id: 'targeting_id',
            tile_assault_id: 'assault_id',
            stealth: 'stealth',
            tile_name: 'name',
            tile_front_image_url: 'front_image_url',
        }),
        ...mapGetters([
            'frontIsPrinterFriendly',
            'frontCutLineColor',
            'frontInvertTitle',
            'frontInvertAbilities',
        ]),
        targeting() {
            return targetingById[this.tile_targeting_id].display_name;
        },
        assault() {
            return targetingById[this.tile_assault_id].display_name;
        },
        assaultIconCssClass() {
            return targetingById[this.tile_assault_id].display_name + '-bg';
        },
        targetingIconCssClass() {
            return targetingById[this.tile_targeting_id].display_name + '-bg';
        },
        assaultNumber() {
            return this.assault.split('D')[1];
        },
        targetingNumber() {
            return this.targeting.split('D')[1];
        },
    },
};

</script>

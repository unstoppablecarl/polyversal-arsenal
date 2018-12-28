<template>
        <g transform="translate(58.4,0)">
            <g :transform="'translate(0,' + weaponGridY +')'">

                <g class="weapon-grid-block">

                    <rect x="137.75" y="0.25" width="14" height="9"/>
                    <rect x="123.75" y="0.25" width="14" height="9"/>
                    <rect x="109.75" y="0.25" width="14" height="9"/>
                    <rect x="95.75" y="0.25" width="14" height="9"/>
                    <rect x="81.75" y="0.25" width="14" height="9"/>
                    <rect x="67.75" y="0.25" width="14" height="9"/>
                    <rect x="53.75" y="0.25" width="14" height="9"/>
                    <rect x="0.25" y="0.25" width="53.5" height="9"/>
                </g>

                <g class="weapon-grid-heading">
                    <text x="144.75" y="5.5">DMG</text>
                    <text x="130.75" y="5.5">AA</text>
                    <text x="116.75" y="5.5">AT</text>
                    <text x="102.75" y="5.5">AP</text>
                    <text x="88.75" y="5.5">RNG</text>
                    <text x="74.75" y="5.5">ARC</text>
                    <text x="60.75" y="5.5">QTY</text>
                    <text x="1.5" y="5.5" class="weapon-grid-heading-left">WEAPON</text>
                </g>
            </g>

            <g v-for="item in tile_weapons" :key="item.display_order">
                <g
                    :transform="'translate(0,' + weaponGridRowY(item.display_order) + ')'"
                >
                    <g class="weapon-grid-block">
                        <rect x="137.75" y="0.25" width="14" height="9"/>
                        <rect x="123.75" y="0.25" width="14" height="9" :class="weaponBlockClass(item.weapon.aa)"/>
                        <rect x="109.75" y="0.25" width="14" height="9" :class="weaponBlockClass(item.weapon.at)"/>
                        <rect x="95.75" y="0.25" width="14" height="9" :class="weaponBlockClass(item.weapon.ap)"/>
                        <rect x="81.75" y="0.25" width="14" height="9"/>
                        <rect x="67.75" y="0.25" width="14" height="9"/>
                        <rect x="53.75" y="0.25" width="14" height="9"/>
                        <rect x="0.25" y="0.25" width="53.5" height="9"/>
                    </g>

                    <g class="weapon-grid-row">
                        <text x="144.75" y="5.5">{{item.weapon.damage | format}}</text>
                        <text x="130.75" y="5.5">{{item.weapon.aa | format}}</text>
                        <text x="116.75" y="5.5">{{item.weapon.at | format}}</text>
                        <text x="102.75" y="5.5">{{item.weapon.ap | format}}</text>
                        <text x="88.75" y="5.5">{{item.weapon.range}}</text>
                        <text x="74.75" y="5.5">ARC</text>
                        <text x="60.75" y="5.5">{{item.quantity}}</text>
                        <text x="1.5" y="5.5" class="weapon-grid-row-left">{{item.weapon.display_name | upper}}</text>
                    </g>
                </g>
            </g>
        </g>

</template>

<script>

    import {
        mapAbilityGetters,
        mapImageGetters,
        mapTileGetters,
        mapTileProperties,
        mapTileWeaponGetters,
    } from '../../data/mappers';
    import {targetingById} from '../../data/options';
    import {NONE} from '../../data/constants';
    import TileSvgDamageTrack from './tile-svg-damge-track';

    export default {
        name: 'tile-weapon-grid-svg',
        components: {TileSvgDamageTrack},
        props: {
            showCutLine: true,
        },
        data() {
            return {};
        },
        computed: {
            ...mapTileWeaponGetters([
                'tile_weapons',
            ]),
            weaponGridY() {
                return (177.949 - this.tile_weapons.length * 9);
            },
        },
        methods: {
            weaponBlockClass(val) {
                if (val === NONE) {
                    return;
                }
                return val + '-bg';
            },
            weaponGridRowY(displayOrder) {
                return (this.weaponGridY + 9 * (displayOrder + 1));
            },
        },
        filters: {
            format(val) {
                if (val === NONE) {
                    return '-';
                }
                return val;
            },
            upper(val) {
                return val.toUpperCase();
            },
        },
    };

</script>

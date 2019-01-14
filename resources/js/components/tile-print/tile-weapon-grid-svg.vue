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
                <text x="60.75" y="5.5">QTY</text>
                <text x="74.75" y="5.5">ARC</text>
                <text x="88.75" y="5.5">RNG</text>
                <text x="102.75" y="5.5">AP</text>
                <text x="116.75" y="5.5">AT</text>
                <text x="130.75" y="5.5">AA</text>
                <text x="144.75" y="5.5">DMG</text>
                <text x="1.5" y="5.5" class="weapon-grid-heading-left">WEAPON</text>
            </g>
        </g>

        <g v-for="item in tileWeapons" :key="item.display_order">
            <g :transform="'translate(0,' + weaponGridRowY(item.display_order) + ')'">
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
                    <g transform="translate(74.75,4.5)">

                        <g :transform="'rotate(' + item.arc_rotation + ')'">
                            <circle class="arc-circle" cx="0" cy="0" r="3"/>

                            <template v-if="item.arc_size_name == '90'">
                                <path class="arc-fov"
                                      d="M-2.1-2.1L0,0l2.1-2.1C1.6-2.7,0.8-3,0-3C-0.8-3-1.6-2.7-2.1-2.1z"/>
                            </template>
                            <template v-else-if="item.arc_size_name == '180'">
                                <path class="arc-fov" d="M0-3c-1.7,0-3,1.3-3,3h6C3-1.7,1.7-3,0-3z"/>
                            </template>

                            <template v-else-if="item.arc_size_name == '270'">
                                <path class="arc-fov" d="M-3,0.1c0,0.8,0.3,1.6,0.9,2.1L0,0.1l2.1,2.1C2.7,1.6,3,0.9,3,0.1c0-1.6-1.3-3-2.9-3c0,0-0.1,0-0.1,0
	C-1.6-3-3-1.6-3,0.1C-3,0-3,0-3,0.1z"/>
                            </template>

                            <template v-else-if="item.arc_size_name == '360'">
                                <circle class="arc-fov" cx="0" cy="0" r="3"/>
                            </template>
                        </g>
                    </g>
                    <!--<text x="74.75" y="5.5">ARC</text>-->
                    <text x="60.75" y="5.5">{{item.quantity}}</text>
                    <text x="1.5" y="5.5" class="weapon-grid-row-left">{{item.weapon.display_name | upper}}</text>
                </g>
            </g>
        </g>
    </g>

</template>

<script>

    import {
        mapTileWeaponGetters,
    } from '../../data/mappers';
    import {arcDirectionById, arcSizeById} from '../../data/options';
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
            ...mapTileWeaponGetters({
                base_tile_weapons: 'tile_weapons',
            }),
            tileWeapons() {
                return this.base_tile_weapons.map((item) => {

                    return Object.assign({}, item, {
                        arc_rotation: arcRotation(item),
                        arc_size_name: arcSizeName(item),
                    });
                });
            },
            weaponGridY() {
                return (177.949 - this.tileWeapons.length * 9);
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

    function arcDirectionName(tileWeapon) {
        let arcDir = arcDirectionById[tileWeapon.arc_direction_id];
        return arcDir.name;
    }

    function arcSizeName(tileWeapon) {
        let arcSize = arcSizeById[tileWeapon.arc_size_id];
        return arcSize.name;
    }

    function arcRotation(tileWeapon) {
        let map       = {
            up: 0,
            left: -90,
            right: 90,
            down: 180,
        };
        let direction = arcDirectionName(tileWeapon);
        return map[direction];
    }


</script>

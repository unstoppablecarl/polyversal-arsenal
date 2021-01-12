<template>
    <g>
        <g :transform="'translate(59,' + (weaponGridY - abilitySize - abilityMargin) +')'">
            <tile-abilities
                :size="abilitySize"
                :margin="abilityMargin"
            />
        </g>
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

            <g v-for="(item, index) in tileWeapons" :key="item.display_order">
                <g :transform="'translate(0,' + weaponGridRowY(index) + ')'">
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
                        <text x="130.75" y="5.5" :class="'weapon-grid-value-' + item.weapon.aa">{{item.weapon.aa | format}}</text>
                        <text x="116.75" y="5.5" :class="'weapon-grid-value-' + item.weapon.at">{{item.weapon.at | format}}</text>
                        <text x="102.75" y="5.5" :class="'weapon-grid-value-' + item.weapon.ap">{{item.weapon.ap | format}}</text>
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
                        <text x="60.75" y="5.5">{{item.quantity}}</text>
                        <text x="1.5" y="5.5" class="weapon-grid-row-left">{{item.weapon.display_name | upper}}</text>

                        <g transform="translate(46,0.75)" v-if="item.is_ama">
                            <path class="icon-ama" d="M2.8,6.8L2.7,6.6H2V5.9l0.5-0.4V3.8l1.1,1.1v0.6l0.5,0.4v0.7H3.5L3.4,6.8H2.8z M1,1.9l0.4-0.4l1.1,1.1l0.1-0.1
	h1l0.9,1.1L4.2,3.7H3.7l0,0l1.8,1.8L5.1,6L1,1.9z M2,3.7L1.7,3.6L2,3.2l0.5,0.5L2,3.7L2,3.7z M2.5,2.4V2.3C2.6,1.7,2.7,1,3.1,1
	c0.3,0,0.5,0.7,0.6,1.3v0.1H2.5z"/>
                        </g>

                        <g transform="translate(44,3)" v-if="item.weapon.is_indirect">
                            <path d="M1.5,0.4l0.2-0.1C2.1,0.1,2.6,0,3.1,0C3.4,0,3.7,0,4,0.1l0.2,0.1L3.9,1.1L3.7,1C3.5,0.9,3.2,0.9,2.9,0.9 c-0.3,0-0.6,0.1-0.9,0.2L1.9,1.1L1.5,0.4z"/>
                            <path d="M0.1,2.2L0.2,2c0.2-0.5,0.5-0.9,0.9-1.3l0.2-0.2l0.5,0.6L1.6,1.3C1.2,1.6,1,1.9,0.8,2.3L0.7,2.5L0.1,2.2z"/>
                            <polygon points="0,3.4 0,2.5 0.7,2.6 0.6,3.4 "/>
                            <polygon points="5.9,4 5.1,4.4 4.4,4.1 5.2,3.7 4.4,3.3 5.1,2.9 5.9,3.3 6.7,2.9 7.4,3.3 6.6,3.7 7.4,4.1 6.7,4.4 "/>
                            <path d="M5.7,3.4L5.2,2.8l0-0.1C5.1,2.2,4.7,1.6,4.2,1.3L4,1.1l0.5-0.8l0.2,0.1c0.8,0.5,1.3,1.3,1.5,2.2l0,0.1L5.9,3.5
	L5.7,3.4z"/>
                        </g>
                    </g>
                </g>
            </g>
        </g>
    </g>
</template>

<script>

    import {
        mapTileProperties,
        mapTileWeaponGetters,
    } from '../../data/mappers'
    import { amaById, arcDirectionById, arcSizeById } from '../../data/options'
    import { AMA_NONE_ID, NONE } from '../../data/constants'
    import TileSvgDamageTrack from './tile-svg-damge-track';
    import TileAbilities from './tile-abilities'

    export default {
        name: 'tile-weapon-grid-svg',
        components: { TileAbilities, TileSvgDamageTrack },
        props: {
            showCutLine: true,
        },
        data() {
            return {
                abilitySize: 10,
                abilityMargin: 2,
            }
        },
        computed: {
            ...mapTileWeaponGetters({
                base_tile_weapons: 'tile_weapons',
            }),
            tileWeapons() {

                let allWeapons = [];
                if (this.hasAMA) {
                    allWeapons.push(this.getAMAItem());
                }

                let weapons = this.base_tile_weapons.map((item) => {
                    return Object.assign({}, item, {
                        arc_rotation: arcRotation(item),
                        arc_size_name: arcSizeName(item),
                        display_order: item.display_order + 1,
                    });
                });
                return allWeapons.concat(weapons);
            },
            weaponGridY() {
                return (177.949 - this.tileWeapons.length * 9);
            },
            ...mapTileProperties({
                tile_ama_id: 'anti_missile_system_id',
            }),
            hasAMA() {
                return this.tile_ama_id !== AMA_NONE_ID;
            },
        },
        methods: {
            getAMAItem() {
                return {
                    weapon: {
                        display_name: 'Anti-Missile',
                        range: 12,
                        aa: amaById[this.tile_ama_id].display_name,
                    },
                    quantity: 1,
                    display_order: 0,
                    arc_rotation: 0,
                    arc_size_name: '360',
                    is_ama: true,
                }
            },
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
        let map = {
            up: 0,
            left: -90,
            right: 90,
            down: 180,
        };
        let direction = arcDirectionName(tileWeapon);
        return map[direction];
    }
</script>

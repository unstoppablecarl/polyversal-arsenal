<template>

    <g class="damage-track" :transform="'translate(' +  (268.3 * 0.5 - totalWidth * 0.5) + ', 0)'">
        <g class="damage-track-header-boxes">

            <rect x="0" y="0" width="15" height="8"/>
            <rect x="15" y="0" :width="cellStressWidth" height="8"/>

            <rect v-for="(val, index) in headerColumns" :key="index"
                  :x="15 + (cellStressWidth) + (index * 10)"
                  y="0"
                  width="10"
                  height="8"
            >
            </rect>
        </g>

        <g class="damage-track-result-boxes">
            <rect x="0" y="8" width="15" height="8"/>
            <rect x="15" y="8" :width="cellStressWidth" height="8"/>
            <rect v-for="(item, index) in resultColumns"
                  :key="item.key"
                  :width="item.range * 10"
                  height="8"
                  :x="item.x"
                  y="8"
            />
        </g>

        <g class="damage-track-header-text">
            <text x="1" y="4.5" class="damage-track-label">DMG</text>
            <text :x="15 + (cellStressWidth * 0.5)" y="4.5">
                <template v-if="stressIsRange">
                    {{stressMin}} - {{stressMax}}
                </template>
                <template v-else>
                    {{stressMax}}
                </template>
            </text>

            <text v-for="(val, index) in headerColumns" :key="index"
                  :x="15 + (cellStressWidth) + (index * 10) + 5"
                  y="4.5"
            >
                {{val}}<tspan v-if="index == headerColumns.length - 1" font-size="6">+</tspan>
            </text>
        </g>

        <g class="damage-track-result-text">
            <text x="1" y="12.5" class="damage-track-label">EFF</text>
            <use :href="'#damage-stress'" :transform="'translate(' + stressCenterX + ', 8)'" class="icon-damage"/>

            <use
                v-for="(item, index) in resultColumns"
                :key="item.key"
                :href="'#' + item.svg_id"
                :transform="'translate(' + resultCenterX(item) + ', 8)'"
                class="icon-damage"
            />
        </g>

    </g>
</template>

<script>
    import _ from 'lodash';
    import {mapAbilityGetters, mapTileGetters, mapTileProperties} from '../../data/mappers';
    import {TILE_TYPE_BUILDING_ID, TILE_TYPE_VEHICLE_ID} from '../../data/constants';

    const columnKeyToSvgId = {
        stress: 'damage-stress',
        jump_jets_offline: 'damage-jump',
        defensive_system_offline: 'damage-defense',
        immobilized: 'damage-immobilized',
        targeting_destroyed: 'damage-targeting',
        weapon_destroyed: 'damage-weapon',
        hull_breach: 'damage-hull-breach',
        fuel_leak: 'damage-fuel-leak',
        destroyed: 'damage-destroyed',
        fire: 'fire'
    };

    const LABEL_CELL_WIDTH = 15;
    const CELL_WIDTH       = 10;

    export default {
        name: 'tile-svg-damage-track',
        props: {},
        data() {
            return {
                stress: 0,
                hull_breach: 0,
                track: null,
                tileColumns: [],
            };
        },
        watch: {},
        methods: {
            resultCenterX(item) {
                let width = (item.range * 10);
                return item.x + (width * 0.5);
            },
        },
        computed: {
            ...mapAbilityGetters([
                'hasJumpJets',
            ]),
            ...mapTileGetters({
                tileDamageTrack: 'damageTrack',
                hasDefensiveSystems: 'hasDefensiveSystems',
            }),
            ...mapTileProperties({
                tile_class_id: 'tile_class_id',
                tile_type_id: 'tile_type_id',
                tile_stealth: 'stealth',
                armor: 'armor',
            }),
            stressCenterX() {
                return 15 + (this.cellStressWidth * 0.5);
            },
            cellStressWidth() {
                if (this.stressIsRange) {
                    return 20;
                }
                return 10;
            },
            damageTrack() {
                let track = Object.assign({}, this.tileDamageTrack);

                if(this.tile_type_id === TILE_TYPE_BUILDING_ID) {
                    track.fire = track.destroyed - 1
                }
                if (this.hasJumpJets && this.tile_type_id === TILE_TYPE_VEHICLE_ID) {
                    if (track.stress > 0) {
                        track.stress -= 1;
                        track.jump_jets_offline = track.stress + 1;
                    }
                }

                if (this.hasDefensiveSystems) {
                    if(this.tile_type_id === TILE_TYPE_BUILDING_ID) {

                        if(track.stress < 3){
                            track.stress -= 1;
                            track.defensive_system_offline = track.stress + 1;
                        } else {
                            track.stress -= 2;
                            track.defensive_system_offline = track.stress + 2;
                        }

                    } else if (track.stress > 0) {
                        track.stress -= 1;
                        track.defensive_system_offline = track.stress + 1;
                    }
                }

                return _.mapValues(track, (val) => parseInt(val, 10) + this.armor);
            },
            stressMin() {
                return this.armor + 1;
            },
            stressMax() {
                return this.damageTrack.stress;
            },
            stressIsRange() {
                return !!this.stressRange;
            },
            stressRange() {
                return this.stressMax - this.stressMin;
            },
            headerColumns() {
                let start = this.damageTrack.stress + 1;
                let end   = this.damageTrack.destroyed + 1;

                let tileColumns = [];
                for (var i = start; i < end; i++) {
                    tileColumns.push(i);
                }

                return tileColumns;
            },
            totalWidth() {
                let total = LABEL_CELL_WIDTH + this.cellStressWidth;
                this.resultColumns.forEach(item => {
                    total += item.range * CELL_WIDTH;
                });
                return total;
            },
            resultColumns: function () {

                let track = _.map(this.damageTrack, (value, key, index) => {
                    return {
                        key,
                        value: parseInt(value, 10),
                    };
                });

                track = track.filter((item) => item.value);
                track = _.sortBy(track, 'value');

                let x = LABEL_CELL_WIDTH + this.cellStressWidth;
                track.forEach((item, index) => {

                    if (index === 0) {
                        return;
                    }

                    let prev   = track[index - 1];
                    item.range = item.value - prev.value;

                    if (item.key == 'destroyed') {
                        item.label = 'X';
                    } else {
                        item.label = item.key.charAt(0).toUpperCase();
                    }

                    //if (item.key == 'defensive_system_offline') {
                    //    item.svg_id = 'damage-defense';
                    //}

                    item.svg_id = columnKeyToSvgId[item.key];

                    item.x = x;

                    x += item.range * CELL_WIDTH;

                });

                track.shift();

                return track;
            },
        },
    };

</script>

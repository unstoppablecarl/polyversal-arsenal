<template>
    <div>
        <div class="damage-track">
            <div class="d-flex justify-content-center damage-track-header">
                <div class="cell cell-dmg">
                    DMG
                </div>
                <div :class="cellStressClass">
                    <template v-if="stressIsRange">
                        {{stressMin}} - {{stressMax}}
                    </template>
                    <template v-else>
                        {{stressMax}}
                    </template>
                </div>

                <div v-for="(val, index) in headerColumns" :key="index" class="cell cell-1">
                    {{val}}
                    <small v-if="index == headerColumns.length - 1">+</small>
                </div>
            </div>
            <div class="d-flex justify-content-center damage-track-results">
                <div class="cell cell-dmg">
                    EFF
                </div>
                <div :class="cellStressClass">
                    S
                </div>
                <div v-for="item in resultColumns"
                     :key="item.key"
                     :class="['cell', 'cell-' + item.range]"
                >
                    {{item.label}}
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import _ from 'lodash';
    import {mapAbilityGetters, mapTileGetters, mapTileProperties} from '../data/mappers';

    export default {
        name: 'tile-damage-track',
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
        computed: {
            ...mapAbilityGetters([
                'hasJumpJets',
            ]),
            ...mapTileGetters({
                tileDamageTrack: 'damageTrack',
                hasDefensiveSystems: 'hasDefensiveSystems',
            }),
            ...mapTileProperties({
                tile_type_id: 'tile_type_id',
                tile_stealth: 'stealth',
                armor: 'armor',
            }),
            cellStressClass() {
                let width = 1;
                if (this.stressIsRange) {
                    width = 2;
                }
                return ['cell', 'cell-' + width];
            },
            damageTrack() {

                let track = Object.assign({}, this.tileDamageTrack);

                if (this.hasJumpJets) {
                    if (track.stress > 0) {
                        track.stress -= 1;
                        track.jump_jets_offline = track.stress + 1;
                    }
                }

                if (this.hasDefensiveSystems) {
                    if (track.stress > 0) {
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
            resultColumns() {

                let track = _.map(this.damageTrack, (value, key, index) => {
                    return {
                        key,
                        value: parseInt(value, 10),
                    };
                });

                track = track.filter((item) => item.value);
                track = _.sortBy(track, 'value');

                track.forEach((item, index) => {

                    if (index === 0) {
                        return;
                    }

                    let prev   = track[index - 1];
                    let range  = item.value - prev.value;
                    item.range = range;

                    if (item.key == 'destroyed') {
                        item.label = 'X';
                    } else {
                        item.label = item.key.charAt(0).toUpperCase();
                    }


                });

                track.shift();

                return track;
            },
        },
    };

</script>

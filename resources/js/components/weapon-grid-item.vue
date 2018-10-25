<template>
    <div class="weapon-grid-item list-item-sortable">
        <div class="col-move">:::</div>
        <div :class="['col-name', {'is-indirect': weapon.is_indirect}, {'is-ama': weapon.is_ama}]">
            <img src="/img/icon-indirect.svg" class="icon-indirect"/>
            <img src="/img/icon-ama.svg" class="icon-ama"/>
            {{weapon.name}}
        </div>
        <div class="col-quantity">
            <input v-model.number="currentQuantity" class="form-control input-quantity"/>
            <div class="label-quantity">{{currentQuantity}}</div>
        </div>
        <div class="col-arc">
            <img :src="arcSvg" :class="arcDirectionClass" class="arc"/>
        </div>
        <div class="col-arc-direction">
            <select class="form-control select-arc-direction" v-model="arcDirection" :disabled="(arcDirection == 'X')">
                <option v-for="(label, key) in arcDirections" v-bind:value="key" v-html="label">
                </option>
            </select>
        </div>
        <div class="col-arc-size">
            <select class="form-control select-arc-size" v-model.number="arcSize">
                <option v-for="label in arcSizes" v-bind:value="label">
                    {{ label }}
                </option>
            </select>
        </div>
        <div class="col-range">{{weapon.range}}</div>
        <div :class="['col-ap', weapon.ap + '-bg']">{{apDisplay}}</div>
        <div :class="['col-at', weapon.at + '-bg']">{{atDisplay}}</div>
        <div :class="['col-aa', weapon.aa + '-bg']">{{aaDisplay}}</div>
        <div class="col-damage">{{damageDisplay}}</div>
        <div class="col-tile-weapon-type">
            <select class="form-control select-tile-weapon-type" v-model.number="currentTileWeaponTypeId"
                    :disabled="tileWeaponTypesLength == 1">
                <option v-for="(label, key) in tileWeaponTypes" v-bind:value="key">
                    {{ label }}
                </option>
            </select>
        </div>
        <div class="col-controls">
            <div class="btn-group no-print">
                <button class="btn btn-sm btn-light" data-tooltip title="Copy" @click="onCopy">
                    <span class="oi oi-layers"></span>
                </button>
                <button class="btn btn-sm btn-danger" @click="onDelete">
                    <span class="oi oi-trash"></span>
                </button>
            </div>
        </div>
    </div>
</template>

<script>
    import {
        NONE, TILE_WEAPON_TYPE_GROUND_ID, TILE_WEAPON_TYPE_SELECT,
    } from '../data/constants';

    export default {
        name: 'weapon-grid-item',
        props: {
            id: {},
            slug: String,
            quantity: Number,
            arc: {
                default: 'UP_90',
            },
            tile_weapon_type_id: {
                default: TILE_WEAPON_TYPE_GROUND_ID,
            },
            display_order: null,
            weaponsRepo: Object,
        },
        data() {
            return {
                currentQuantity: this.quantity,
                arcDirection: this.arc.split('_')[0],
                i_arcSize: this.arc.split('_')[1],
                arcDirections: {
                    'UP': '&uparrow;',
                    'LEFT': '&leftarrow;',
                    'RIGHT': '&rightarrow;',
                    'DOWN': '&downarrow;',
                },
                arcSizes: [
                    '90',
                    '180',
                    '270',
                    '360',
                ],
                currentTileWeaponTypeId: this.tile_weapon_type_id,
            };
        },
        computed: {
            apDisplay() {
                return formatNone(this.weapon.ap);
            },
            atDisplay() {
                return formatNone(this.weapon.at);
            },
            aaDisplay() {
                return formatNone(this.weapon.aa);
            },
            damageDisplay() {
                return formatNone(this.weapon.damage);
            },
            currentArc() {
                return this.arcDirection + '_' + this.arcSize;
            },
            arcDirectionClass() {
                return 'arc-' + this.arcDirection.toLowerCase();
            },
            arcSvg() {
                return '/img/arc-' + this.arcSize + '.svg';
            },
            arcSize: {
                get() {
                    return this.i_arcSize;
                },
                set(val) {
                    if (val === 360) {
                        this.arcDirection = 'X';
                    } else if (this.arcDirection === 'X') {
                        this.arcDirection = 'UP';
                    }
                    this.i_arcSize = val;
                },
            },
            hasGround() {
                return this.weaponsRepo.hasGround(this.slug);
            },
            hasWithAA() {
                return this.weaponsRepo.hasWithAA(this.slug);
            },
            hasOnlyAA() {
                return this.weaponsRepo.hasOnlyAA(this.slug);
            },
            tileWeaponTypes() {
                return this.weaponsRepo.tileWeaponTypeSelect(this.slug);
            },
            tileWeaponTypesLength() {
                return Object.keys(this.tileWeaponTypes).length;
            },
            weapon() {
                let item = this.weaponsRepo.get(this.slug, this.currentTileWeaponTypeId);

                return _.pick(item, [
                    'name',
                    'aa',
                    'at',
                    'ap',
                    'is_ama',
                    'is_indirect',
                    'damage',
                    'range',
                ]);
            },
        },
        watch: {
            currentQuantity(val) {
                this.$store.dispatch('weaponUpdate', this.toModel());
            },
        },
        methods: {
            toModel() {
                return {
                    id: this.id,
                    slug: this.slug,
                    tile_weapon_type_id: this.currentTileWeaponTypeId,
                    quantity: this.currentQuantity,
                    arc: this.currentArc,
                    display_order: this.display_order,
                };
            },
            onCopy() {
                this.$store.dispatch('weaponCopy', this.toModel());
            },
            onDelete() {
                this.$store.dispatch('weaponDelete', this.toModel());
            },
        },
    };

    function formatNone(val) {
        if (val === NONE) {
            return '-';
        }
        return val;
    }

</script>

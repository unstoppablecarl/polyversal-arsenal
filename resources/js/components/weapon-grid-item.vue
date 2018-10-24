<template>
    <div class="weapon-grid-item list-item-sortable">
        <div class="col-move">:::</div>
        <div class="col-name">{{name}}</div>
        <div class="col-quantity">
            <input v-model.number="currentQuantity" class="form-control"/>
        </div>
        <div class="col-arc">
            <img :src="'/img/arc-' + arcSize + '.svg'" :class="'arc-' + arcDirection" class="arc"/>
        </div>
        <div class="col-arc-size">
            <select class="form-control select-arc-direction" v-model="arcDirection" :disabled="(arcDirection == 'X')">
                <option v-for="label in arcDirections" v-bind:value="label">
                    {{ label }}
                </option>
            </select>
        </div>
        <div class="col-arc-direction">
            <select class="form-control select-arc-size" v-model.number="arcSize">
                <option v-for="label in arcSizes" v-bind:value="label">
                    {{ label }}
                </option>
            </select>
        </div>
        <div class="col-range">{{range}}</div>
        <div :class="['col-ap', ap + '-bg']">{{apDisplay}}</div>
        <div :class="['col-at', at + '-bg']">{{atDisplay}}</div>
        <div :class="['col-aa', aa + '-bg']">{{aaDisplay}}</div>
        <div class="col-damage">{{damageDisplay}}</div>
        <div class="col-controls">
            <template v-if="is_ama">
                AMA
            </template>
            <div class="btn-group no-print">
                <button class="btn btn-sm btn-light" data-tooltip title="Copy" @click="onCopy">
                    <span class="oi oi-layers"></span>
                </button>
                <button class="btn btn-sm btn-danger" data-tooltip title="Delete" @click="onDelete">
                    <span class="oi oi-trash"></span>
                </button>
            </div>
        </div>
    </div>
</template>

<script>
    import {
        NONE, TILE_WEAPON_TYPE_GROUND_ID,
    } from '../data/constants';

    export default {
        name: 'weapon-grid-item',
        props: {
            id: Number,
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
        mounted() {

            let item = this.weaponsRepo.get(this.slug, this.tile_weapon_type_id);

            item = _.pick(item, [
                'name',
                'aa',
                'at',
                'ap',
                'is_ama',
                'is_indirect',
                'damage',
                'range',
            ]);
            Object.assign(this, item);
        },
        data() {
            return {
                name: null,
                aa: null,
                at: null,
                ap: null,
                is_ama: null,
                is_indirect: null,
                range: null,
                damage: null,
                currentQuantity: this.quantity,
                arcDirection: this.arc.split('_')[0],
                i_arcSize: this.arc.split('_')[1],
                arcDirections: [
                    'UP',
                    'LEFT',
                    'RIGHT',
                    'DOWN',
                ],
                arcSizes: [
                    '90',
                    '180',
                    '270',
                    '360',
                ],
            };
        },
        computed: {
            apDisplay() {
                return formatNone(this.ap);
            },
            atDisplay() {
                return formatNone(this.at);
            },
            aaDisplay() {
                return formatNone(this.aa);
            },
            damageDisplay() {
                return formatNone(this.damage);
            },
            currentArc() {
                return this.arcDirection + '_' + this.arcSize;
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
                    tile_weapon_type_id: this.tile_weapon_type_id,
                    quantity: this.currentQuantity,
                    arc: this.currentArc,
                    display_order: this.display_order,
                };
            },
            onCopy() {
                let weapon = this.toModel();
                let newIndex = weapon.display_order + 1;

                this.$store.dispatch('weaponCreate', weapon)
                    .then((newWeapon) => {
                        this.$store.dispatch('weaponMove', {weapon: newWeapon, newIndex});
                    });
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

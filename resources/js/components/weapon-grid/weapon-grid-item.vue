<template>
    <tr class="weapon-grid-item list-item-sortable">
        <td class="col-move">:::</td>
        <td :class="['col-name', {'is-indirect': weapon.is_indirect}]">
            <img src="/img/icon-indirect.svg" class="icon-indirect"/>
            {{weapon.display_name}}
        </td>
        <td class="col-quantity">
            <input v-model.number="currentQuantity" class="form-control input-quantity" @blur="checkMinQuantity"/>
        </td>
        <td class="col-arc">
            <div class="arc" :class="[arcDirectionClass, arcSizeClass]"></div>
        </td>
        <td class="col-arc-direction">
            <select class="form-control select-arc-direction" v-model="arcDirectionId"
                    :disabled="(arcSize.name == '360')">
                <option v-for="(direction, key) in arcDirections" v-bind:value="key" v-html="direction">
                </option>
            </select>
        </td>
        <td class="col-arc-size">
            <select class="form-control select-arc-size" v-model.number="arcSizeId">
                <option v-for="size in arcSizeOptions" v-bind:value="size.id">
                    {{size.display_name}}
                </option>
            </select>
        </td>
        <td class="col-range">{{weapon.range}}</td>
        <td :class="['col-ap', weapon.ap + '-bg']">{{weapon.ap | format}}</td>
        <td :class="['col-at', weapon.at + '-bg']">{{weapon.at | format}}</td>
        <td :class="['col-aa', weapon.aa + '-bg']">{{weapon.aa | format}}</td>
        <td class="col-damage">{{weapon.damage | format}}</td>
        <td class="col-tile-weapon-type">
            <select class="form-control select-tile-weapon-type" v-model.number="tileWeaponTypeId">
                <option v-for="(label, key) in tileWeaponTypes" :key="key" :value="key">
                    {{label}}
                </option>
            </select>
        </td>
        <td class="col-cost">
            {{cost}}
        </td>
        <td class="col-total">
            {{total_cost}}
        </td>
        <td class="col-controls">
            <div class="btn-group no-print">
                <button class="btn btn-sm btn-light" data-tooltip title="Copy" @click="onCopy">
                    <span class="fas fa-fw fa-copy"></span>
                </button>
                <button class="btn btn-sm btn-danger" @click="onDelete">
                    <span class="fas fa-fw fa-trash"></span>
                </button>
            </div>
        </td>
    </tr>
</template>

<script>
    import {
        NONE,
        TILE_WEAPON_TYPE_GROUND_ID,
        TILE_WEAPON_TYPE_ONLY_AA_ID,
        TILE_WEAPON_TYPE_WITH_AA_ID,
    } from '../../data/constants';

    import {arcDirectionById, arcSizeById, arcSizeOptions} from '../../data/options';
    import {mapTileWeaponGetters} from '../../data/mappers';

    export default {
        name: 'weapon-grid-item',
        props: {
            id: {},
            weapon_id: Number,
            weapon: Object,
            quantity: null,
            total_cost: Number,
            cost: Number,
            arc_direction_id: Number,
            arc_size_id: Number,
            tile_weapon_type_id: {
                default: TILE_WEAPON_TYPE_GROUND_ID,
            },
            display_order: null,
        },
        data() {
            return {
                arcDirections: {
                    1: '&uparrow;',
                    2: '&leftarrow;',
                    3: '&rightarrow;',
                    4: '&downarrow;',
                },
                arcSizeOptions,
                tileWeaponTypes: {
                    [TILE_WEAPON_TYPE_GROUND_ID]: 'Ground',
                    [TILE_WEAPON_TYPE_WITH_AA_ID]: 'With AA',
                    [TILE_WEAPON_TYPE_ONLY_AA_ID]: 'Only AA',
                },
            };
        },
        computed: {
            ...mapTileWeaponGetters({
                tile_weapons: 'tile_weapons',
                getWeapon: 'getWeapon'
            }),
            currentQuantity: {
                get() {
                    return this.quantity
                },
                set(value) {
                    this.update({quantity: value});
                }
            },
            tileWeaponTypeId: {
                get() {
                    return this.tile_weapon_type_id;
                },
                set(value) {
                    this.update({tile_weapon_type_id: value});
                }
            },
            arcDirectionId: {
                get() {
                    return this.arc_direction_id;
                },
                set(value) {
                    this.update({arc_direction_id: value});
                }
            },
            arcSizeId: {
                get() {
                    return this.arc_size_id;
                },
                set(value) {
                    value = parseInt(value, 10);

                    const ARC_DIRECTION_UP_ID = 1;
                    const ARC_SIZE_360_ID     = 4;
                    if (value == ARC_SIZE_360_ID) {
                        this.arcDirectionId = ARC_DIRECTION_UP_ID;
                    }
                    this.update({arc_size_id: value});
                }
            },
            arcDirectionClass() {
                const direction = arcDirectionById[this.arcDirectionId];
                return 'arc-' + direction.name.toLowerCase();
            },
            arcSizeClass() {
                return 'arc-' + this.arcSize.name
            },
            arcSize() {
                return arcSizeById[this.arcSizeId];
            },
        },
        methods: {
            toModel() {
                let {
                        id,
                        weapon_id,
                        tile_weapon_type_id,
                        quantity,
                        arc_direction_id,
                        arc_size_id,
                        display_order,
                    } = this;
                return {
                    id,
                    weapon_id,
                    tile_weapon_type_id,
                    quantity,
                    arc_direction_id,
                    arc_size_id,
                    display_order,
                };
            },
            update(data) {
                let update = Object.assign({id: this.id}, data);
                this.$store.dispatch('tile_weapons/update', update);
            },
            onCopy() {
                this.$store.dispatch('tile_weapons/copy', this.toModel());
            },
            onDelete() {
                this.$store.dispatch('tile_weapons/delete', this.toModel());
            },
            checkMinQuantity() {
                if (this.currentQuantity < 1) {
                    this.currentQuantity = 1;
                }
            },
        },
        filters: {
            format(val) {
                if (val === NONE) {
                    return '-';
                }
                return val;
            },
        },
    };

</script>

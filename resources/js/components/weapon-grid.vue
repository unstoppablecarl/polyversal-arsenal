<template>
    <div :class="[{'scale-2': scale}]">
        <div :class="[{'no-select': dragging}]">

            <table class="table weapon-grid">
                <thead>
                <tr class="weapon-grid-header">
                    <th class="col-move"></th>
                    <th class="col-name">Weapon</th>
                    <th class="col-quantity">Qty</th>
                    <th class="col-arc">Arc</th>
                    <th class="col-arc-direction">Dir</th>
                    <th class="col-arc-size">Size</th>
                    <th class="col-range">RNG</th>
                    <th class="col-ap">AP</th>
                    <th class="col-at">AT</th>
                    <th class="col-aa">AA</th>
                    <th class="col-damage">DMG</th>
                    <th class="col-tile-weapon-type">Type</th>
                    <th class="col-cost">Cost</th>
                    <th class="col-total">Total</th>
                    <th class="col-controls"></th>
                </tr>
                </thead>

                <weapon-grid-ama/>

                <draggable
                    tag="tbody"
                    class=""
                    :list="tile_weapons"
                    ghostClass="sortable-ghost"
                    chosenClass="sortable-chosen"
                    :animation="200"
                    :preventOnFilter="false"
                    :forceFallback="true"
                    draggable=".list-item-sortable"
                    handle=".col-move"
                    @change="onSortableChange"
                    @start="dragging = true"
                    @end="dragging = false"
                >
                    <weapon-grid-item
                        v-for="item in tile_weapons"
                        :key="item.display_order"
                        :id="item.id"
                        :weapon_id="item.weapon_id"
                        :quantity="item.quantity"
                        :cost="item.cost"
                        :weapon="item.weapon"
                        :total_cost="item.total_cost"
                        :tile_weapon_type_id="item.tile_weapon_type_id"
                        :display_order="item.display_order"
                        :arc_direction_id="item.arc_direction_id"
                        :arc_size_id="item.arc_size_id"
                        :tile_type_id="tile_type_id"
                    />
                </draggable>
            </table>
        </div>
    </div>
</template>

<script>
    import WeaponGridItem from './weapon-grid/weapon-grid-item';
    import draggable from 'vuedraggable';
    import WeaponGridAma from './weapon-grid/weapon-grid-ama';
    import {mapTileProperties, mapTileWeaponGetters} from '../data/mappers';

    export default {
        name: 'weapon-grid',
        components: {
            WeaponGridAma,
            WeaponGridItem,
            draggable,
        },
        props: {
            scale: {
                default: true,
            },
        },
        data() {
            return {
                dragging: false,
            };
        },
        methods: {
            onSortableChange(event) {
                let moved = event.moved;
                if (!moved) {
                    return;
                }

                let settings = {
                    weapon: moved.element,
                    newIndex: moved.newIndex,
                };

                this.$store.dispatch('tile_weapons/move', settings);
            },
        },
        computed: {
            ...mapTileWeaponGetters([
                'tile_weapons',
            ]),
            ...mapTileProperties({
                tile_type_id: 'tile_type_id',
            }),
            loading() {
                return this.$store.getters.asyncState === 'fetching';
            },
        },
    };
</script>

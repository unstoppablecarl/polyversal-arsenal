<template>
    <div :class="[{'scale-2': scale}]">
        <div :class="['weapon-grid', {'no-select': dragging}]">

            <div class="container weapon-grid-list">
                <div class="row grid-header">
                    <div class="col-move"></div>
                    <div class="col-name">Weapon</div>
                    <div class="col-quantity">Qty</div>
                    <div class="col-arc">Arc</div>
                    <div class="col-arc-direction">Dir</div>
                    <div class="col-arc-size">Size</div>
                    <div class="col-range">RNG</div>
                    <div class="col-ap">AP</div>
                    <div class="col-at">AT</div>
                    <div class="col-aa">AA</div>
                    <div class="col-damage">DMG</div>
                    <div class="col-tile-weapon-type">Type</div>
                    <div class="col-cost">Cost</div>
                    <div class="col-controls"></div>
                </div>

                <weapon-grid-ama/>

                <draggable
                    class="list-group"
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
                    <transition-group type="transition">

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
                    </transition-group>
                </draggable>
            </div>
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

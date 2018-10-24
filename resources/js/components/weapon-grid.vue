<template>
    <div :class="['weapon-grid', {'no-select': dragging}]">
        <div class="container weapon-grid-list">
            <div class="row grid-header">
                <div class="col-move"></div>
                <div class="col-name">Weapon</div>
                <div class="col-quantity">Qty</div>
                <div class="col-arc">Arc</div>
                <div class="col-arc-size">Size</div>
                <div class="col-arc-direction">Dir</div>
                <div class="col-range">RNG</div>
                <div class="col-ap">AP</div>
                <div class="col-at">AT</div>
                <div class="col-aa">AA</div>
                <div class="col-damage">DMG</div>
                <div class="col-controls"></div>
            </div>

            <draggable class="list-group"
                       :list="weapons"
                       :options="sortableOptions"
                       @change="onSortableChange"
                       @start="dragStart"
                       @end="dragEnd"
            >
                <transition-group type="transition">

                    <weapon-grid-item
                        v-for="item in weapons"
                        :key="item.id"
                        :id="item.id"
                        :slug="item.slug"
                        :quantity="item.quantity"
                        :tile_weapon_type_id="item.tile_weapon_type_id"
                        :display_order="item.display_order"
                        :arc="item.arc"
                        :tile_type_id="tile_type_id"
                        :weaponsRepo="repo"
                    />
                </transition-group>
            </draggable>
        </div>

        <weapon-grid-item-new
            v-on:add="addWeapon"
            :tile_type_id="tile_type_id"
            :weaponsRepo="repo"
        />
    </div>
</template>

<script>
    import WeaponGridItem from './weapon-grid-item';
    import draggable from 'vuedraggable';
    import WeaponGridItemNew from './weapon-grid-item-new';
    import Weapons from '../data/weapons';
    import {TILE_TYPE_VEHICLE_ID} from '../data/constants';


    export default {
        name: 'weapon-grid',
        components: {
            WeaponGridItemNew,
            WeaponGridItem,
            draggable,
        },
        props: {
            tile_type_id: {
                type: Number,
                default: TILE_TYPE_VEHICLE_ID,
            },
        },
        data() {
            return {
                dragging: false,
            };
        },
        methods: {
            addWeapon(selectedWeapon, tileWeaponTypeId) {
                this.$store.dispatch('weaponCreate', {
                    slug: selectedWeapon,
                    tile_weapon_type_id: tileWeaponTypeId,
                    arc: 'UP_90',
                });
            },
            onSortableChange(event) {
                let moved = event.moved;
                if (!moved) {
                    return;
                }

                let settings = {
                    weapon: moved.element,
                    newIndex: moved.newIndex,
                };

                this.$store.dispatch('weaponMove', settings);
            },
            dragStart() {
                this.dragging = true;
            },
            dragEnd() {
                this.dragging = false;
            },
        },
        computed: {
            repo() {
                return Weapons(this.tile_type_id);
            },
            weapons() {
                return this.$store.getters.weapons;
            },
            loading() {
                return this.$store.getters.asyncState === 'fetching';
            },
            sortableOptions() {
                return {
                    ghostClass: 'sortable-ghost',
                    chosenClass: 'sortable-chosen',  // Class name for the chosen item
                    //filter: 'button:not(.col-move)',  // Selectors that do not lead to dragging (String or Function)
                    preventOnFilter: false,
                    forceFallback: true,
                    draggable: '.list-item-sortable',
                    handle: '.col-move',
                };
            },
        },
    };

</script>

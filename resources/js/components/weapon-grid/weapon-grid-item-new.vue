<template>
    <div class="container weapon-grid-item-new">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Quick Add Weapon</label>
            <div class="col-sm-4">
                <select class="form-control" v-model="selectedWeaponId">
                    <option v-for="item in options" v-bind:value="item.id">{{ item.display_name }}</option>
                </select>
            </div>
            <div class="col-sm-4">
                <weapon-grid-add-buttons :weapon-id="selectedWeaponId"/>
            </div>
        </div>
    </div>
</template>

<script>
    import {mapTileProperties, mapTileWeaponGetters} from '../../data/mappers';
    import WeaponGridAddButtons from "./weapon-grid-add-buttons";

    export default {
        name: 'weapon-grid-item-new',
        components: {WeaponGridAddButtons},
        data() {
            return {
                selectedWeaponId: null,
            };
        },
        created() {
            this.selectFirst();
        },
        watch: {
            tile_type_id() {
                this.selectFirst();
            },
        },
        methods: {
            selectFirst() {
                this.selectedWeaponId = this.firstValidWeaponId;
            },
        },
        computed: {
            ...mapTileProperties({
                tile_type_id: 'tile_type_id',
            }),
            ...mapTileWeaponGetters([
                'options',
                'firstValidWeaponId'
            ])
        },
    };
</script>

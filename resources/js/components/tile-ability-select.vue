<template>
    <div class="form-group row">
        <label class="col-sm-2 col-form-label">{{title}}</label>
        <div class="col-sm-4">
            <b-dropdown variant="light" :text="dropDownText" :disabled="disabled">
                <b-dropdown-header>
                    <div class="row">
                        <div class="col-sm-4 text-right">
                            Value
                        </div>
                        <div class="col-sm-2 text-right">
                            Cost
                        </div>

                    </div>
                </b-dropdown-header>

                <b-dropdown-item v-for="item in options" :key="item.id"
                                 @click="selectOption(item.id)"
                                 :active="item.id == selected_id"
                >
                    <div class="row">
                        <div class="col-sm-4 text-right">
                            {{item.display_name}}
                        </div>
                        <div class="col-sm-2 text-right">
                            <number :val="item.cost" :invert="true"/>
                        </div>
                    </div>
                </b-dropdown-item>
            </b-dropdown>
        </div>
        <div class="col-sm-1 col-form-label number-cell">
            <number :val="selectedItem.cost" :invert="true"/>
        </div>
        <div class="col-sm col-form-label">
            <span class="text-primary" data-tooltip :title.once="defensiveSystemTooltip" v-if="isDefensiveSystem">
                    Defensive System
            </span>
        </div>
    </div>
</template>

<script>
    import Number from './number';
    import _ from 'lodash';
    import {mapTileProperties} from '../data/mappers';
    import {TILE_TYPE_VEHICLE_ID} from '../data/constants';
    import {defensiveSystemTooltip} from '../content/tooltips';

    export default {
        name: 'tile-ability-select',
        components: {Number},
        model: {
            prop: 'selected_id',
            event: 'select_option_id',
        },
        props: {
            title: String,
            options: Array,
            selected_id: Number,
            isDefensive: {
                default: false,
            },
            showMove: {
                default: true,
            },
            showEvasion: {
                default: true,
            },
            disabled: {
                default: false,
            },
        },
        data(){
          return {
              defensiveSystemTooltip
          }
        },
        computed: {
            ...mapTileProperties({
                tile_type_id: 'type_id',
            }),
            isDefensiveSystem() {
                if (this.tile_type_id === TILE_TYPE_VEHICLE_ID) {
                    return this.isDefensive;
                }
                return false;
            },
            selectedItem() {
                return _.find(this.options, (row) => {
                    return row.id == this.selected_id;
                });
            },
            dropDownText() {
                if (this.disabled) {
                    return 'None';
                }
                return this.selectedItem.display_name;
            },
        },
        methods: {
            selectOption(id) {
                this.$emit('select_option_id', id);
            },
        },
    };

</script>

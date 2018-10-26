<template>

    <div class="form-group row">
        <label class="col-sm-2 col-form-label">Class</label>
        <div class="col-sm-4">
            <b-dropdown variant="light" :text="selectedLabel" :disabled="disabled">
                <b-dropdown-item v-for="item in options" :key="item.id"
                                 @click="selectOption(item.id)"
                                 :active="item.id == selected_id"
                >
                    Class {{item.id}} {{item.label}}

                </b-dropdown-item>
            </b-dropdown>
        </div>
        <div class="col-sm-1 col-form-label number-cell">
            <strong>Cost</strong>
        </div>
        <div class="col-sm-1 col-form-label number-cell">
            <strong>Move</strong>
        </div>
        <div class="col-sm-1 col-form-label number-cell">
            <strong>Evasion</strong>
        </div>
    </div>
</template>

<script>
    import _ from 'lodash';
    import {vehicleClasses} from '../data/class';

    export default {
        name: 'tile-class-row',
        model: {
            prop: 'selected_id',
            event: 'select_option_id',
        },
        props: {
            selected_id: Number,
            disabled: null,
        },
        data() {
            return {
                options: vehicleClasses,
            };
        },
        computed: {
            selectedLabel() {
                if (this.disabled) {
                    return 'None';
                }
                return 'Class ' + this.selectedItem.id + ' ' + this.selectedItem.label;
            },
            selectedItem() {
                return _.find(this.options, (row) => {
                    return row.id == this.selected_id;
                });
            },
        },
        methods: {
            selectOption(id) {
                this.$emit('select_option_id', id);
            },
        },
    };

</script>

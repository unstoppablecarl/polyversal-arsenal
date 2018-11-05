<template>
    <div class="form-group row">
        <label class="col-sm-2 col-form-label">Class</label>
        <div class="col-sm-4">
            <b-dropdown variant="light" :text="selectedDisplayName" :disabled="disabled">
                <b-dropdown-item v-for="item in options" :key="item.id"
                                 @click="selectOption(item.id)"
                                 :active="item.id == selected_id"
                >
                    {{optionDisplayName(item)}}
                </b-dropdown-item>
            </b-dropdown>
        </div>
    </div>
</template>

<script>
    import _ from 'lodash';
    import {vehicleClassOptions} from '../data/options';

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
                options: vehicleClassOptions,
            };
        },
        computed: {
            selectedDisplayName() {
                if (this.disabled) {
                    return 'None';
                }
                return this.optionDisplayName(this.selectedItem);
            },
            selectedItem() {
                return _.find(this.options, (row) => {
                    return row.id == this.selected_id;
                });
            },
        },
        methods: {
            optionDisplayName(item) {
                return 'Class ' + item.id + ' ' + item.display_name;
            },
            selectOption(id) {
                this.$emit('select_option_id', id);
            },
        },
    };

</script>

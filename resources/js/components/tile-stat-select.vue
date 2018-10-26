<template>
    <div class="form-group row">
        <label class="col-sm-2 col-form-label">{{label}}</label>
        <div class="col-sm-4">
            <b-dropdown variant="light" :text="selectedItem.label">
                <b-dropdown-header>
                    <div class="row">
                        <div class="col-sm-4 text-right">
                            {{label}}
                        </div>
                        <div class="col-sm text-right">
                            Cost
                        </div>
                        <div class="col-sm text-right">
                            Move
                        </div>
                        <div class="col-sm text-right">
                            Evasion
                        </div>
                    </div>
                </b-dropdown-header>

                <b-dropdown-item v-for="item in options" :key="item.id"
                                 @click="selectOption(item.id)"
                                 :active="item.id == selected_id"
                >
                    <div class="row">
                        <div class="col-sm-4 text-right">
                            {{item.label}}
                        </div>
                        <div class="col-sm text-right">
                            <number :val="item.cost" :invert="true"/>
                        </div>
                        <div class="col-sm text-right">
                            <number :val="item.move || 0"/>
                        </div>
                        <div class="col-sm text-right">
                            <number :val="item.evasion || 0" />
                        </div>
                    </div>
                </b-dropdown-item>
            </b-dropdown>
        </div>
        <div class="col-sm-1 col-form-label number-cell">
            <number :val="selectedItem.cost" :invert="true"/>
        </div>
        <div class="col-sm-1 col-form-label number-cell">
            <number :val="selectedItem.move || 0"/>
        </div>
        <div class="col-sm-1 col-form-label number-cell">
            <number :val="selectedItem.evasion || 0"/>
        </div>
    </div>
</template>

<script>
    import Number from './number';
    import _ from 'lodash';

    export default {
        name: 'tile-stat-select',
        components: {Number},
        model: {
            prop: 'selected_id',
            event: 'select_option_id',
        },
        props: {
            label: String,
            options: Array,
            selected_id: Number,
        },
        computed: {
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

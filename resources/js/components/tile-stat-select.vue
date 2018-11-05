<template>
    <div class="form-group row">
        <label class="col-sm-2 col-form-label">{{title}}</label>
        <div :class="dropDownColumnClass">
            <b-dropdown variant="light" :text="dropDownText" :disabled="disabled">
                <b-dropdown-header>
                    <div class="row">
                        <div class="col-sm-4 text-right">
                            {{title}}
                        </div>
                        <div class="col-sm text-right">
                            Cost
                        </div>
                        <div class="col-sm text-right">
                            <template v-if="showMove">
                                Move
                            </template>
                        </div>
                        <div class="col-sm text-right">
                            <template v-if="showEvasion">
                                Evasion
                            </template>
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
                        <div class="col-sm text-right">
                            <number :val="item.cost" :invert="true"/>
                        </div>
                        <div class="col-sm text-right">
                            <number :val="item.move || 0" v-if="showMove"/>
                        </div>
                        <div class="col-sm text-right">
                            <number :val="item.evasion || 0" v-if="showEvasion"/>
                        </div>
                    </div>
                </b-dropdown-item>
            </b-dropdown>
        </div>
        <div class="col-sm-1 col-form-label number-cell">
            <number :val="selectedItem.cost" :invert="true"/>
        </div>
        <div class="col-sm-1 col-form-label number-cell">
            <number :val="selectedItem.move || 0" v-if="showMove"/>
        </div>
        <div class="col-sm-1 col-form-label number-cell">
            <number :val="selectedItem.evasion || 0" v-if="showEvasion"/>
        </div>
        <div class="col-sm col-form-label">
            <slot name="notes"></slot>
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
            title: String,
            options: Array,
            selected_id: Number,
            showMove: {
                default: true,
            },
            showEvasion: {
                default: true,
            },
            disabled: {
                default: false,
            },
            dropDownColumnSize: {
                default: 4,
            },
        },
        computed: {
            dropDownColumnClass() {
                return 'col-sm-' + this.dropDownColumnSize;
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

<template>

    <div class="form-group row">
        <label class="col-sm-2 col-form-label">Classification</label>
        <div class="col-sm-4">
            <b-dropdown variant="light" :text="selectedItem.display_name">
                <b-dropdown-item v-for="item in options" :key="item.id"
                                 @click="selectOption(item.id)"
                                 :active="item.id == selected_id"
                >

                    <div class="row">
                        <div class="col-sm-6">
                            {{item.display_name}}
                        </div>

                    </div>

                </b-dropdown-item>
            </b-dropdown>
        </div>

    </div>

</template>

<script>
    import _ from 'lodash';
    import {tileTypeOptions} from '../../data/options';

    export default {
        name: 'tile-classification-row',
        model: {
            prop: 'selected_id',
            event: 'select_option_id',
        },
        props: {
            selected_id: Number,
        },
        data() {
            return {
                options: tileTypeOptions,
            };
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

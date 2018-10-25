<template>
    <div class="form-group row">
        <label class="col-sm-2 col-form-label">{{label}}</label>
        <div class="col-sm-4">
            <b-dropdown variant="light" :text="selected_name">
                <b-dropdown-header>
                    <div class="row">
                        <div class="col-sm-4 text-right">

                        </div>
                        <div class="col-sm-2 text-right">
                            Cost
                        </div>
                        <div class="col-sm-2 text-right">
                            Move
                        </div>
                        <div class="col-sm-2 text-right">
                            Evasion
                        </div>
                    </div>
                </b-dropdown-header>

                <b-dropdown-item v-for="item in options" :key="item.id" @click="selectOption(item.id)">
                    <div class="row">
                        <div class="col-sm-4 text-right">
                            {{item.label}}
                        </div>
                        <div class="col-sm-2 text-right">
                            <number :val="item.cost" :invert="true"/>
                        </div>
                        <div class="col-sm-2 text-right">
                            <number :val="item.move"/>
                        </div>
                        <div class="col-sm-2 text-right">
                            <number :val="item.evasion"/>
                        </div>
                    </div>
                </b-dropdown-item>
            </b-dropdown>
        </div>
        <div class="col-sm-1 col-form-label number-cell">
            <number :val="selected_cost" :invert="true"/>
        </div>
        <div class="col-sm-1 col-form-label number-cell">
            <number :val="selected_move"/>
        </div>
        <div class="col-sm-1 col-form-label number-cell">
            <number :val="selected_evasion"/>
        </div>
    </div>
</template>

<script>
    import Number from './number';

    export default {
        name: 'tile-stat-select',
        components: {Number},
        model: {
            prop: 'selected_id',
            event: 'select_option_id'
        },
        props: {
            label: String,
            options: Array,
            selected_id: Number,
            selected_cost: Number,
            selected_move: Number,
            selected_evasion: Number,
            selected_name: String,
        },
        methods: {
            selectOption(id){
                this.$emit('select_option_id', id);
            }
        },
    };

</script>

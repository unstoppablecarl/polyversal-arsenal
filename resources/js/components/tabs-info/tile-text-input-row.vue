<template>
    <div class="form-group row">
        <label class="col-sm-2 col-form-label">{{label}}</label>
        <div class="col-sm-4">
            <input :value="value" @input="input" class="form-control"/>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'tile-text-input-row',
        model: {
            prop: 'value',
            event: 'input',
        },
        props: {
            label: null,
            value: null,
        },
        methods: {
            input(event) {
                this.$emit('input', event.target.value);
            },
        },
    };

</script>

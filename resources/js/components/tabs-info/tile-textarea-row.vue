<template>
    <div class="form-group row">
        <label class="col-sm-2 col-form-label">{{ label }}</label>
        <div class="col-sm-4">
            <textarea @input="input" class="form-control">{{value}}</textarea>
        </div>
        <div class="col-sm-6">
            <p>{{ desc }}</p>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'tile-textarea-row',
        model: {
            prop: 'value',
            event: 'input',
        },
        props: {
            label: null,
            value: null,
            desc: null,
        },
        methods: {
            input(event) {
                this.$emit('input', event.target.value);
            },
        },
    };

</script>

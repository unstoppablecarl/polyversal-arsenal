<template>
    <button :class="['btn btn-primary', {inline: 'btn-inline'}]" @click="save" :disabled="syncing">
        <template v-if="saving">
            Saving
            <i class="fas fa-fw fa-spin fa-cog"></i>
        </template>
        <template v-else>
            Save
            <i class="fas fa-fw fa-save"></i>
        </template>
    </button>
</template>

<script>
    import {mapGetters} from 'vuex';

    export default {
        name: 'btn-save',
        props: {
            inline: false,
        },
        computed: {
            ...mapGetters([
                'saving',
                'syncing',
            ]),
        },
        methods: {
            save() {
                this.$store.dispatch('save')
            },
        }
    };

</script>

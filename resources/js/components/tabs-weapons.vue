<template>

    <div>

        <weapon-grid
            :scale="true"
            :print-preview="false"
        />

        <weapon-grid-item-new/>

    </div>

</template>

<script>

    import WeaponGrid from './weapon-grid';
    import WeaponGridItemNew from './weapon-grid-item-new';

    export default {
        name: 'tabs-weapons',
        components: {
            WeaponGridItemNew,
            WeaponGrid,
        },
        props: {
            name: String,
        },
        data() {
            return {};
        },
        computed: {},
        watch: {},
        methods: {},
    };

</script>

<template>

    <div>
        <div class="form-group row header-row">
            <div class="col-sm-6 col-form-label">
                Weapons
            </div>

        </div>
        <div class="tab-content-divider"></div>
        <weapon-grid
            :scale="true"
        />

        <weapon-grid-item-new/>

    </div>

</template>

<script>

    import WeaponGrid from './weapon-grid';
    import WeaponGridItemNew from './weapon-grid/weapon-grid-item-new';

    export default {
        name: 'tabs-weapons',
        components: {
            WeaponGridItemNew,
            WeaponGrid,
        },
        data() {
            return {};
        },
        computed: {},
        watch: {},
        methods: {},
    };

</script>

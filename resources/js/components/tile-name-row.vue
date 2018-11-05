<template>
    <div class="form-group row">
        <label class="col-sm-2 col-form-label"> Name</label>
        <div class="col-sm-4">
            <input v-model="tile_name" class="form-control"/>
        </div>
    </div>
</template>

<script>
    import {mapTileProperties} from '../data/mappers';

    export default {
        name: 'tile-name-row',

        props: {
            label: null,
        },
        data() {
            return {};
        },
        computed: {
            ...mapTileProperties({
                tile_name: 'name',
            }),
        },
        methods: {},
    };

</script>

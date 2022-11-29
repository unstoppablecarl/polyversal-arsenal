<template>
    <tile-grid
    :columns="columns"/>
</template>

<script>

    import TileGrid from './tile-grid/tile-grid';

    export default {
        name: 'app-tile-grid',
        components: {TileGrid},
        data(){
            return {
                columns:  [
                    {
                        field: 'id',
                        label: 'ID',
                    },
                    {
                        field: 'name',
                        label: 'Name',
                    },
                    {
                        field: 'tile_type',
                        label: 'Type',
                    },
                    {
                        field: 'tile_class',
                        label: 'Class',
                    },
                    {
                        field: 'tile_mobility',
                        label: 'Mobility',
                    },
                    {
                        field: 'tech_level',
                        label: 'Tech Level',
                    },
                    {
                        field: 'move',
                        label: 'Move',
                        type: 'number',
                    },
                    {
                        field: 'evasion',
                        label: 'Evasion',
                        type: 'number',

                    },
                    {
                        field: 'armor',
                        label: 'Armor',
                        type: 'number',

                    },
                    {
                        field: 'targeting',
                        label: 'Targeting',
                    },
                    {
                        field: 'assault',
                        label: 'Assault',
                    },
                    {
                        field: 'cached_cost',
                        label: 'Cost',
                        type: 'number',
                    },
                    {
                        field: 'updated_at',
                        label: 'Updated',
                        type: 'timestamp',
                    },
                ],
            }
        }
    }
</script>

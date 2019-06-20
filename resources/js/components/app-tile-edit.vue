<template>
    <div>
        <router-view></router-view>
    </div>
</template>

<script>
    import Tile from './tile'

    export default {
        name: 'app-tile-edit',
        components: {
            Tile,
        },
    }

</script>

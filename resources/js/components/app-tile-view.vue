<template>
    <div>
        <tile-print/>
    </div>
</template>

<script>

import TilePrint from './tile-print'

export default {
    name: 'app-tile-view',
    components: {TilePrint},
    props: {
        tileId: {
            required: true,
        },
    },
    mounted() {
        this.$store.dispatch('fetch', this.tileId)
            .then((result) => {
                if (result) {
                    if (result.not_found || result.unauthorized) {
                        this.notFound = true
                    }
                }
            })
    }
}

</script>

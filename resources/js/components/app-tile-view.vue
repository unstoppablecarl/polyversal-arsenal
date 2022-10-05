<template>
    <div>
        <tile-print v-if="tileLoaded"/>
    </div>
</template>

<script>

import TilePrint from './tile-print'
export default {
    name: 'app-tile-view',
    components: {TilePrint},
    props: {
        tileId: {
            // required: true,
        },
    },
    mounted() {
        if (!this.tileId) {
            return
        }
        this.$store.dispatch('fetch', this.tileId)
            .then((result) => {
                if (result) {
                    if (result.not_found || result.unauthorized) {
                        this.notFound = true
                    }
                }
            })
    },
    computed:{
        tileLoaded(){
            return this.$store.getters['tile/id']
        },
    },
}

</script>

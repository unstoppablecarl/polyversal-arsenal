<template>
    <div>
        <button class="btn btn-light" @click="savePng">Save PNG</button>
        <button class="btn btn-light" @click="saveSvg">Save SVG</button>
    </div>
</template>

<script>
    import downloadDataURL from '../../lib/download-data-url';

    export default {
        name: 'tile-print-card-save',
        props: {
            storeNamespace: false,
        },
        computed: {
            fileName() {
                return this.$store.getters[this.storeNamespace + '/fileName'];
            },
        },
        methods: {
            saveSvg() {
                this.$store.dispatch(this.storeNamespace + '/getSvgBase64')
                    .then((base64) => {
                        let fileName = this.fileName + '.svg';
                        downloadDataURL(base64, fileName);
                    });
            },
            savePng() {
                this.$store.dispatch(this.storeNamespace + '/getImageBase64')
                    .then((base64) => {
                        let fileName = this.fileName + '.png';
                        downloadDataURL(base64, fileName);
                    });
            },
        },
    };
</script>

<template>
    <div class="card">
        <div class="card-body">

            <div class="row">
                <div class="col-md-4">
                    <input type="file" v-on:change="onFileChange" class="form-control-file">
                </div>
                <div class="col-md-4">
                    <figure class="figure">
                        <figcaption class="figure-caption">Current Image</figcaption>
                        <template v-if="image_url">
                            <img :src="image_url" class="figure-img img-fluid img-thumbnail">
                        </template>
                    </figure>
                </div>
                <div class="col-md-4">

                    <figure class="figure">
                        <figcaption class="figure-caption">New Image</figcaption>
                        <template v-if="new_image_data">
                            <img :src="new_image_data" class="figure-img img-fluid img-thumbnail">
                        </template>
                    </figure>

                </div>

            </div>
        </div>

    </div>
</template>
<script>
    import {mapTileProperties} from '../data/mappers';

    export default {
        name: 'file-upload',
        data() {
            return {};
        },
        computed: {
            ...mapTileProperties({
                tile_id: 'id',
                new_image_data: 'new_image_data',
                image_url: 'image_url',
            }),
        },
        methods: {
            onFileChange(e) {
                let files = e.target.files || e.dataTransfer.files;
                if (!files.length)
                    return;
                this.createImage(files[0]);
            },
            createImage(file) {
                let reader    = new FileReader();
                reader.onload = (e) => {
                    this.$store.dispatch('tile/setNewImageData', e.target.result);
                };
                reader.readAsDataURL(file);
            },
        },
    };
</script>

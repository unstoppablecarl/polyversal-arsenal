<template>
    <div>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">{{label}}</label>
            <div class="col-sm-4">
                <input type="file" v-on:change="onFileChange" class="form-control-file" ref="file_input">
            </div>
            <div class="col-md-3">

                <figure class="figure">
                    <figcaption class="figure-caption">New Image</figcaption>
                    <template v-if="newImageData">
                        <img :src="newImageData" class="figure-img img-fluid img-thumbnail">
                    </template>
                </figure>

            </div>
            <div class="col-md-3">
                <figure class="figure">
                    <figcaption class="figure-caption">Current Image</figcaption>
                    <template v-if="imageUrl">
                        <img :src="imageUrl" class="figure-img img-fluid img-thumbnail">
                    </template>
                </figure>
            </div>
        </div>

    </div>
</template>
<script>
    import {mapImageGetters, mapTileProperties} from '../../data/mappers';

    export default {
        name: 'file-upload',
        props: {
            label: null,
            imageUrl: null,
            newImageData: null,
        },
        watch: {
            newImageData(value) {
                if (!value) {
                    this.clearImage();
                }
            },
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
                    this.$emit('imageSet', e.target.result);
                };
                reader.readAsDataURL(file);
            },
            clearImage() {
                this.$refs.file_input.value = null;
            },
        },
    };
</script>

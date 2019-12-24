<template>
    <div class="card card-upload">
        <div class="card-header">
            <h4>{{label}}</h4>
        </div>
        <div class="card-body">

            <div class="row">
                <div class="col-sm-4">
                    <input type="file" v-on:change="onFileChange" class="form-control-file" ref="file_input">
                    <br>

                    <p :class="{'text-danger': fileTooBig}">
                        <strong>File Size:</strong> {{newImageSizeDisplay}} <strong v-if="fileTooBig">Image too big</strong>
                    </p>
                    <p>
                        <strong>Max Size:</strong> {{maxImageSizeMb}} mb
                    </p>
                    <p>
                        <strong>Note:</strong> Uploading images does not automatically save the finished tile.
                    </p>
                    <template v-if="unsavedChanges">

                        <p class="text-danger">
                            Changes to this background image have not been saved to the finished tile.

                            Don't forget to
                            <btn-save :inline="true"/>
                            when you are done making changes.
                        </p>
                    </template>
                </div>
                <div class="col-md-4">
                    <figure class="figure">
                        <figcaption class="figure-caption">New Image</figcaption>

                        <img :src="newImageSrc" class="figure-img img-fluid img-thumbnail">

                    </figure>
                    <p>
                        <button class="btn btn-success" @click="onUpload" v-if="newImageData" :disabled="syncing">
                            <template v-if="uploading">
                                Uploading
                                <i class="fas fa-fw fa-spin fa-cog"></i>
                            </template>
                            <template v-else>
                                Upload
                                <i class="fas fa-fw fa-save"></i>
                            </template>
                        </button>
                        <button class="btn btn-light" @click="onCancelUpload" v-if="newImageData && !uploading">Cancel
                        </button>
                    </p>
                </div>
                <div class="col-md-4">
                    <figure class="figure">
                        <figcaption class="figure-caption">Current Image</figcaption>

                        <img :src="currentImageSrc" class="figure-img img-fluid img-thumbnail">

                    </figure>

                    <p class="text-danger">
                        <strong>
                            <template v-if="deleteClicked">
                                Are you sure you want to Delete this image?
                            </template>
                            <template v-else>
                                &nbsp;
                            </template>
                        </strong>
                    </p>
                    <button class="btn btn-danger" @click="onDelete" v-if="imageUrl" :disabled="syncing">
                        <template v-if="deleting">
                            Deleting
                            <i class="fas fa-fw fa-spin fa-cog"></i>
                        </template>
                        <template v-else-if="deleteClicked">
                            Yes Delete It
                        </template>
                        <template v-else>
                            Delete
                            <i class="fas fa-fw fa-trash"></i>
                        </template>
                    </button>
                    <button class="btn btn-light" @click="onCancelDelete" v-if="deleteClicked">Cancel</button>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    import {mapGetters} from 'vuex';
    import BtnSave from '../btn-save';
    import fileSize from 'filesize';

    export default {
        name: 'file-upload',
        components: {BtnSave},
        props: {
            label: null,
            imageUrl: null,
            uploadAction: null,
            deleteAction: null,
            unsavedChanges: null,
        },
        watch: {
            imageUrl() {
                this.clearUpload();
            },
        },
        data() {
            return {
                newImageData: null,
                newImageSizeMb: null,
                newImageSizeDisplay: null,
                deleteClicked: false,
                deleteConfirmed: false,
                uploading: false,
                deleting: false,
            };
        },
        computed: {
            ...mapGetters([
                'syncing',
            ]),
            newImageSrc() {
                return this.newImageData || '/img/background-placeholder.png';
            },
            currentImageSrc() {
                return this.imageUrl || '/img/background-placeholder.png';
            },
        },
        methods: {
            clearUpload() {
                this.newImageData           = null;
                this.$refs.file_input.value = null;
            },
            onFileChange(e) {
                let files = e.target.files || e.dataTransfer.files;
                if (!files.length) {
                    return;
                }
                let file                 = files[0];
                this.newImageSizeDisplay = fileSize(file.size, {base: 10});
                this.newImageSizeMb      = file.size * 1000000;
                this.createImage(file);
            },
            createImage(file) {
                let reader    = new FileReader();
                reader.onload = (e) => {
                    this.newImageData = e.target.result;
                };
                reader.readAsDataURL(file);
            },

            onUpload() {
                this.uploading = true;
                return this.$store.dispatch(this.uploadAction, this.newImageData)
                    .then(() => {
                        this.uploading = false;
                    });
            },
            onCancelUpload() {
                this.clearUpload();
            },
            onDelete() {
                if (!this.deleteClicked) {
                    this.deleteClicked = true;
                    return;
                }
                this.deleteClicked = false;
                this.deleting      = true;
                this.$store.dispatch(this.deleteAction)
                    .then(() => {
                        this.deleting = false;
                    });
            },
            onCancelDelete() {
                this.deleteClicked = false;
            },
        },
    };
</script>

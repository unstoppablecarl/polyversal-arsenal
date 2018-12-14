<template>
    <div>

        <div class="form-group row header-row">
            <div class="col-sm-6 col-form-label">
                Info
            </div>
        </div>

        <div class="tab-content-divider"></div>

        <tile-text-input-row
            label="Name"
            v-model="tile_name"
        />

        <tile-textarea-row
            label="Flavor Text"
            v-model="flavor_text"
        />

        <file-upload
            label="Front Image"
            :image-url="frontSourceImageUrl"
            :new-image-data="newFrontSourceImageBase64"
            @imageSet="frontImageLoaded"
        />

    </div>

</template>

<script>
    import {mapImageGetters, mapTileProperties} from '../data/mappers';

    import FileUpload from './tabs-info/file-upload';
    import TileTextInputRow from './tabs-info/tile-text-input-row';
    import TileTextareaRow from './tabs-info/tile-textarea-row';

    export default {
        name: 'tabs-info',
        components: {
            TileTextareaRow,
            TileTextInputRow,
            FileUpload,
        },
        computed: {
            ...mapTileProperties({
                tile_name: 'name',
                flavor_text: 'flavor_text',
            }),
            ...mapImageGetters([
                'frontSourceImageUrl',
                'newFrontSourceImageBase64',
            ]),
        },
        methods: {
            frontImageLoaded(value) {
                this.$store.dispatch('images/setNewFrontSourceImageBase64', value);
            },
        },
    };

</script>

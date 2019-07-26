<template>
    <div class="card">

        <div class="card-header">
            <div class="float-right">
                <tile-print-card-save
                    store-namespace="images/front"
                />
            </div>
            <h3 class="card-title">Title Front</h3>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-3 text-right">Options</div>
                            <div class="col-sm-9">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" v-model="frontIsPrinterFriendly"
                                           id="frontIsPrinterFriendly">
                                    <label class="form-check-label" for="frontIsPrinterFriendly">
                                        Printer Friendly
                                    </label>
                                </div>

                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" v-model="frontInvertTitle"
                                           id="frontInvertTitle">
                                    <label class="form-check-label" for="frontInvertTitle">
                                        Invert Title
                                    </label>
                                </div>

                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" v-model="frontInvertAbilities"
                                           id="frontInvertAbilities">
                                    <label class="form-check-label" for="frontInvertAbilities">
                                        Invert Abilities
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label text-right">Cut Line Color</label>
                        <div class="col-sm-9">
                            <select v-model="frontCutLineColor">
                                <option v-for="(label, value) in cutLineColorOptions" :key="value"
                                        :value="value">{{label}}
                                </option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <slot></slot>
                </div>
            </div>
        </div>
    </div>

</template>

<script>
    import FileUpload from '../tabs-info/file-upload';
    import downloadDataURL from '../../lib/download-data-url';
    import {mapTilePrintSettingsProperties} from "../../store/tile-print-settings-mappers";
    import {mapGetters} from "vuex";
    import TilePrintCardSave from "./tile-print-card-save";

    export default {
        name: 'tile-front-print-card',
        components: {FileUpload, TilePrintCardSave},
        props: {},
        data() {
            return {};
        },
        computed: {
            ...mapGetters([
                'cutLineColorOptions'
            ]),
            ...mapTilePrintSettingsProperties([
                'frontIsPrinterFriendly',
                'frontInvertTitle',
                'frontInvertAbilities',
                'frontCutLineColor',
            ]),
        },
        methods: {
            saveSvg() {
                this.$store.dispatch('images/front' + '/getSvgBase64')
                    .then((base64) => {
                        let fileName = this.fileName + '.svg';
                        downloadDataURL(base64, fileName);
                    });
            },
            savePng() {
                this.$store.dispatch('images/front' + '/getImageBase64')
                    .then((base64) => {
                        let fileName = this.fileName + '.png';
                        downloadDataURL(base64, fileName);
                    });
            },
        },
    };

</script>


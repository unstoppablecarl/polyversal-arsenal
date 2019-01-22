<template>
    <div class="card">
        <div class="card-header">
            <div class="float-right">
                <button class="btn btn-light" @click="savePng">Save PNG</button>
                <button class="btn btn-light" @click="saveSvg">Save SVG</button>
            </div>
            <h3 class="card-title">{{title}}</h3>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-3 text-right">Options</div>
                            <div class="col-sm-9">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" v-model="showCutLine"
                                           :id.once="showCutLineId">
                                    <label class="form-check-label" :for.once="showCutLineId">
                                        Show Cut Line
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" v-model="printerFriendly"
                                           :id.once="printerFriendlyId">
                                    <label class="form-check-label" :for.once="printerFriendlyId">
                                        Printer Friendly
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label text-right">Cut Line Color</label>
                        <div class="col-sm-9">
                            <select v-model="cutLineColor">
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

    export default {
        name: 'tile-print-card',
        components: {FileUpload},
        props: {
            title: String,
            idPrefix: String,
            storeNamespace: false,
        },
        data() {
            return {
                showCutLineId: this.idPrefix + '-showCutLine',
                printerFriendlyId: this.idPrefix + '-printerFriendly',
            };
        },
        created() {
        },
        beforeCreate() {
        },
        computed: {
            ...mapNamespacedProps([
                'printerFriendly',
                'showCutLine',
                'cutLineColor',
            ]),

            ...mapNamespacedGetters([
                'cutLineColorOptions',
                'fileName',
            ]),
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

    function mapNamespacedProps(keys) {
        return mapKeys(keys, mapGetSet);
    }

    function mapNamespacedGetters(keys) {
        return mapKeys(keys, namespacedGetter);
    }

    function mapKeys(keys, method) {
        let result = {};

        keys.forEach((key) => {
            result[key] = method(key);
        });

        return result;
    }

    function mapGetSet(key) {
        return {
            get: namespacedGetter(key),
            set: namespacedSetter(key),
        };
    }

    function namespacedGetter(key) {
        return function () {
            return this.$store.getters[this.storeNamespace + '/' + key];
        };
    }

    function namespacedSetter(key) {
        return function (value) {
            this.$store.dispatch(this.storeNamespace + '/' + key, value);
        };
    }
</script>

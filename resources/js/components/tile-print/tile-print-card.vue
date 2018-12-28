<template>
    <div class="card">
        <div class="card-header">
            <div class="float-right">
                <button class="btn btn-light" @click="$emit('savePng')">Save PNG</button>
                <button class="btn btn-light" @click="$emit('saveSvg')">Save SVG</button>
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
                                <option value="red">Red</option>
                                <option value="blue">Blue</option>
                                <option value="black">Black</option>
                                <option value="white">White</option>
                            </select>
                        </div>
                    </div>

                </div>
                <div class="col-sm-6">
                    <slot
                        :showCutLine="showCutLine"
                        :cutLineColor="cutLineColor"
                        :printerFriendly="printerFriendly"
                    ></slot>

                </div>
            </div>
        </div>
    </div>

</template>

<script>
    import {mapImageGetters} from '../../data/mappers';
    import FileUpload from '../tabs-info/file-upload';

    export default {
        name: 'tile-print-card',
        components: {FileUpload},
        props: {
            title: String,
            idPrefix: String,
        },
        data() {
            return {
                showCutLine: false,
                showCutLineId: this.idPrefix + '-showCutLine',
                cutLineColor: 'red',
                printerFriendly: false,

                printerFriendlyId: this.idPrefix + '-printerFriendly',
            };
        },
        computed: {
            ...mapImageGetters([
                'frontSourceImageUrl',
                'newFrontSourceImageBase64',

                'backSourceImageUrl',
                'newBackSourceImageBase64',
            ]),
        },
        methods: {

        },
    };

</script>

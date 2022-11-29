<template>
    <div>
        <div class="form-group">
            <div class="row">
                <div class="col-sm-3 text-right">Options</div>
                <div class="col-sm-9">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" v-model="backIsPrinterFriendly"
                               id="back_is_printer_friendly">
                        <label class="form-check-label" for="back_is_printer_friendly">
                            Printer Friendly
                        </label>
                    </div>

                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" v-model="backInvertTitle"
                               id="back_invert_title">
                        <label class="form-check-label" for="back_invert_title">
                            Invert Title
                        </label>
                    </div>

                    <div class="form-check">
                        <input class="form-check-input" type="checkbox"
                               v-model="backInvertBottomHeadings"
                               id="backInvertBottomHeadings">
                        <label class="form-check-label" for="backInvertBottomHeadings">
                            Invert Bottom Headings
                        </label>
                    </div>

                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" v-model="backInvertFlavorText"
                               id="backInvertFlavorText">
                        <label class="form-check-label" for="backInvertFlavorText">
                            Invert Flavor Text
                        </label>
                    </div>

                </div>
            </div>
        </div>

        <div class="form-group row">
            <label class="col-sm-3 col-form-label text-right">Cut Line Color</label>
            <div class="col-sm-9">
                <select v-model="backCutLineColor">
                    <option v-for="(label, value) in cutLineColorOptions" :key="value"
                            :value="value">{{ label }}
                    </option>
                </select>
            </div>
        </div>
    </div>

</template>

<script>
import {mapTilePrintSettingsProperties} from "../../store/tile-print-settings-mappers";
import {mapGetters} from "vuex";

export default {
    name: 'tile-back-print-card-body',
    props: {},
    data() {
        return {};
    },
    computed: {
        ...mapGetters([
            'cutLineColorOptions'
        ]),
        ...mapTilePrintSettingsProperties([
            'backIsPrinterFriendly',
            'backInvertTitle',
            'backInvertBottomHeadings',
            'backInvertFlavorText',
            'backInvertAbilities',
            'backCutLineColor',
        ]),
    },
};

</script>


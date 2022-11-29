<template>

    <div>
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
    name: 'tile-front-print-card-body',
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
};

</script>


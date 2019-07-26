import {defAction, defGetter, defMutation} from "./helpers/store-mappers";
import {keyBy, mapValues} from "lodash";

const bindings = {
    front_is_printer_friendly: {
        getter: 'frontIsPrinterFriendly',
        setter: 'setFrontIsPrinterFriendly',
    },
    front_invert_title: {
        getter: 'frontInvertTitle',
        setter: 'setFrontInvertTitle',
    },
    front_invert_abilities: {
        getter: 'frontInvertAbilities',
        setter: 'setFrontInvertAbilities',
    },
    front_cut_line_color: {
        getter: 'frontCutLineColor',
        setter: 'setFrontCutLineColor',
    },

    back_is_printer_friendly: {
        getter: 'backIsPrinterFriendly',
        setter: 'setBackIsPrinterFriendly',
    },
    back_invert_title: {
        getter: 'backInvertTitle',
        setter: 'setBackInvertTitle',
    },
    back_invert_bottom_headings: {
        getter: 'backInvertBottomHeadings',
        setter: 'setBackInvertBottomHeadings',
    },
    back_invert_flavor_text: {
        getter: 'backInvertFlavorText',
        setter: 'setBackInvertFlavorText',
    },
    back_cut_line_color: {
        getter: 'backCutLineColor',
        setter: 'setBackCutLineColor',
    }
};

let gettersToSetters = keyBy(bindings, 'getter')
gettersToSetters = mapValues(gettersToSetters, item => item.setter);

export const mutations = {};
export const actions = {};
export const getters = {};

Object.keys(bindings)
    .forEach((stateKey) => {
        let {setter, getter} = bindings[stateKey];
        mutations[setter] = defMutation(stateKey);
        actions[setter] = defAction(setter);
        getters[getter] = defGetter(stateKey);
    });


export function mapTilePrintSettingsProperties(getters) {
    let out = {};

    getters.forEach(getter => {
        let setter = gettersToSetters[getter];

        out[getter] = {
            get() {
                return this.$store.getters[getter];
            },
            set(val) {
                this.$store.dispatch(setter, val);
            },
        };
    });

    return out;
}

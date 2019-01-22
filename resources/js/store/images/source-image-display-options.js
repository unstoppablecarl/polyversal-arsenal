import {defActions, defGetters, defMutations} from '../helpers/store-mappers';

export default {
    namespaced: false,

    state: {

        showCutLine: false,
        cutLineColor: 'red',
        printerFriendly: false,

        prev: {},

        cutLineColorOptions: {
            red: 'Red',
            blue: 'Blue',
            white: 'White',
            black: 'Black',
        },
    },
    mutations: {
        clearDisplaySettings(state) {
            let {showCutLine, cutLineColor, printerFriendly} = state;

            state.prev = {
                showCutLine,
                cutLineColor,
                printerFriendly,
            };

            state.showCutLine     = false;
            state.cutLineColor    = 'none';
            state.printerFriendly = false;
        },
        restoreDisplaySettings(state) {
            state.showCutLine     = state.prev.showCutLine;
            state.cutLineColor    = state.prev.cutLineColor;
            state.printerFriendly = state.prev.printerFriendly;
        },
        ...defMutations([
            'printerFriendly',
            'cutLineColor',
            'showCutLine',
        ]),
    },
    actions: {
        ...defActions([
            'printerFriendly',
            'cutLineColor',
            'showCutLine',
        ]),
    },
    getters: {
        ...defGetters([
            'printerFriendly',
            'cutLineColor',
            'showCutLine',
            'cutLineColorOptions',
        ]),
    },
};


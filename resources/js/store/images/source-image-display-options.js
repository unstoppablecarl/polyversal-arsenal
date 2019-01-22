import {mapActions, mapGetters, mapMutations} from '../helpers/store-mappers';

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
        ...mapMutations([
            'printerFriendly',
            'cutLineColor',
            'showCutLine',
        ]),
    },
    actions: {
        ...mapActions([
            'printerFriendly',
            'cutLineColor',
            'showCutLine',
        ]),
    },
    getters: {
        ...mapGetters([
            'printerFriendly',
            'cutLineColor',
            'showCutLine',
            'cutLineColorOptions',
        ]),
    },
};


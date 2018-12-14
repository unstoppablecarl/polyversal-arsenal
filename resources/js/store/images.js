import frontSourceImages from './images/front-images';
import backSourceImages from './images/back-images';

export default {
    namespaced: true,

    state: {},

    actions: {},
    getters: {
        tileSize() {
            let inchHeight = 3.25;
            let inchWidth  = 3.71;

            let width  = inchWidth * 300;
            let height = inchHeight * 300;

            return {
                width,
                height,
            };
        },
        fileName(state, getters, rootState) {
            return rootState.tile.name.replace(/[^a-z0-9]/gi, '_').toLowerCase();
        },
    },
    modules: {
        frontSourceImages,
        backSourceImages,
    },
};


import toDataURL from '../../lib/image-to-base64';
import svgToBase64 from '../../lib/svg-to-base64';
import svgToPngBase64 from '../../lib/svg-to-png-base64';

export default {
    state: {
        back_source_image_url: null,
        back_source_image_base64: null,
        back_source_image_base64_new: null,
    },
    mutations: {
        setNewBackSourceImageBase64(state, data) {
            state.back_source_image_base64     = null;
            state.back_source_image_base64_new = data;
        },
        setBackSourceImageBase64(state, value) {
            state.back_source_image_base64 = value;
        },
        setBackSourceImageUrl(state, url) {
            state.back_source_image_base64     = null;
            state.back_source_image_base64_new = null;
            state.back_source_image_url = url;
        },
    },
    actions: {
        setNewBackSourceImageBase64({commit}, data) {
            commit('setNewBackSourceImageBase64', data);
        },
        setBackSourceImageUrl({commit}, url) {
            commit('setBackSourceImageUrl', url);
        },
        loadBackSourceImageBase64FromUrl({state, commit}) {
            let imageBase64 = state.back_source_image_base64;
            let imageUrl    = state.back_image_url;

            if (!imageBase64 && imageUrl) {
                return toDataURL(imageUrl, 'png')
                    .then((dataURL) => {
                        commit('setBackSourceImageBase64', dataURL);
                    });
            }

            return Promise.resolve();
        },
        getBackSvgBase64({dispatch}) {
            return dispatch('loadBackSourceImageBase64FromUrl')
                .then(() => {
                    return svgToBase64('tile-back-svg');
                });
        },
        getBackImageBase64({dispatch, getters}) {
            return dispatch('loadBackSourceImageBase64FromUrl')
                .then(() => {

                    let {width, height} = getters.tileSize;

                    return svgToPngBase64('tile-back-svg', width, height);
                });
        },
    },
    getters: {
        backSourceImageUrl(state) {
            if (state.back_source_image_base64) {
                return state.back_source_image_base64;
            }
            return state.back_source_image_url;
        },
        newBackSourceImageBase64(state) {
            return state.back_source_image_base64_new;
        },
        backFileName(state, getters) {
            return 'polyversal-tile-back-' + getters.fileName;
        },
    },
};



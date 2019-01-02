import toDataURL from '../../lib/image-to-base64';
import svgToPngBase64 from '../../lib/svg-to-png-base64';
import svgToString from '../../lib/svg-to-string';
import svgToBase64 from '../../lib/svg-to-base64';

export default {
    state: {
        front_source_image_delete: false,
        front_source_image_url: null,
        front_source_image_base64: null,
        front_source_image_base64_new: null,
    },
    mutations: {
        setNewFrontSourceImageBase64(state, data) {
            state.front_source_image_base64     = null;
            state.front_source_image_delete     = false;
            state.front_source_image_base64_new = data;
        },
        setFrontSourceImageBase64(state, value) {
            state.front_source_image_base64 = value;
        },
        setFrontSourceImageUrl(state, url) {
            state.front_source_image_base64     = null;
            state.front_source_image_base64_new = null;
            state.front_source_image_delete     = false;
            state.front_source_image_url        = url;
        },
        setFrontSourceImageDelete(state, value) {
            state.front_source_image_delete = value;
        },
    },
    actions: {
        setNewFrontSourceImageBase64({commit}, data) {
            commit('setNewFrontSourceImageBase64', data);
        },
        setFrontSourceImageUrl({commit}, url) {
            commit('setFrontSourceImageUrl', url);
        },
        setFrontSourceImageDelete({commit}, value) {
            commit('setFrontSourceImageDelete', value);
        },
        loadFrontSourceImageBase64FromUrl({state, commit}) {
            let imageBase64 = state.front_source_image_base64;
            let imageUrl    = state.front_source_image_url;

            if (!imageBase64 && imageUrl) {
                return toDataURL(imageUrl, 'png')
                    .then((dataURL) => {
                        commit('setFrontSourceImageBase64', dataURL);
                    });
            }

            return Promise.resolve();
        },
        getFrontSvgString({dispatch}) {
            return dispatch('loadFrontSourceImageBase64FromUrl')
                .then(() => {
                    return svgToString('tile-front-svg');
                });
        },
        getFrontSvgBase64({dispatch}) {
            return dispatch('loadFrontSourceImageBase64FromUrl')
                .then(() => {
                    return svgToBase64('tile-front-svg');
                });
        },
        getFrontImageBase64({dispatch, getters}) {
            return dispatch('loadFrontSourceImageBase64FromUrl')
                .then(() => {

                    let {width, height} = getters.tileSize;

                    return svgToPngBase64('tile-front-svg', width, height);
                });
        },
    },
    getters: {
        frontSourceImageUrl(state) {
            if (state.front_source_image_base64) {
                return state.front_source_image_base64;
            }
            return state.front_source_image_url;
        },
        newFrontSourceImageBase64(state) {
            return state.front_source_image_base64_new;
        },
        frontImageDelete(state) {
            return state.front_source_image_delete;
        },
        frontFileName(state, getters) {
            return 'polyversal-tile-front-' + getters.fileName;
        },
    },
};



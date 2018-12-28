import toDataURL from '../../lib/image-to-base64';
import svgToPngBase64 from '../../lib/svg-to-png-base64';
import svgToString from '../../lib/svg-to-string';
import svgToBase64 from '../../lib/svg-to-base64';

export default function makeTileImageModule({svgId, fileNamePrefix}) {

    return {
        namespaced: true,
        state: {
            source_image_url: null,
            source_image_base64: null,
            source_image_base64_new: null,
            show_cut_line: false,
            cut_line_color: false,
        },
        mutations: {
            setNewSourceImageBase64(state, data) {
                state.source_image_base64     = null;
                state.source_image_base64_new = data;
            },
            setSourceImageBase64(state, value) {
                state.source_image_base64 = value;
            },
            setSourceImageUrl(state, url) {
                state.source_image_base64     = null;
                state.source_image_base64_new = null;
                state.source_image_url        = url;
            },
        },
        actions: {
            setNewSourceImageBase64({commit}, data) {
                commit('setNewSourceImageBase64', data);
            },
            setSourceImageUrl({commit}, url) {
                commit('setSourceImageUrl', url);
            },
            loadSourceImageBase64FromUrl({state, commit}) {
                let imageBase64 = state.source_image_base64;
                let imageUrl    = state.source_image_url;

                if (!imageBase64 && imageUrl) {
                    return toDataURL(imageUrl, 'png')
                        .then((dataURL) => {
                            commit('setSourceImageBase64', dataURL);
                        });
                }

                return Promise.resolve();
            },
            getSvgString({dispatch}) {
                return dispatch('loadSourceImageBase64FromUrl')
                    .then(() => {
                        return svgToString(svgId);
                    });
            },
            getSvgBase64({dispatch}) {
                return dispatch('loadSourceImageBase64FromUrl')
                    .then(() => {
                        return svgToBase64(svgId);
                    });
            },
            getImageBase64({dispatch, getters}) {
                return dispatch('loadSourceImageBase64FromUrl')
                    .then(() => {

                        let {width, height} = getters.tileSize;

                        return svgToPngBase64(svgId, width, height);
                    });
            },
        },
        getters: {
            sourceImageUrl(state) {
                if (state.source_image_base64) {
                    return state.source_image_base64;
                }
                return state.source_image_url;
            },
            newSourceImageBase64(state) {
                return state.source_image_base64_new;
            },
            fileName(state, getters) {
                return fileNamePrefix + getters.fileName;
            },
        },
    };
}





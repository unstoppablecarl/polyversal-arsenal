import toDataURL from '../../lib/image-to-base64';

export default {
    state: {
        front_source_image_url: null,
        front_source_image_base64: null,
        front_source_image_base64_new: null,
    },
    mutations: {
        setNewFrontSourceImageBase64(state, data) {
            state.front_source_image_base64     = null;
            state.front_source_image_base64_new = data;
        },
        setFrontSourceImageBase64(state, value) {
            state.front_source_image_base64 = value;
        },
        setFrontSourceImageUrl(state, url) {
            state.front_source_image_base64     = null;
            state.front_source_image_base64_new = null;
            state.front_source_image_url = url;
        },
    },
    actions: {
        setNewFrontSourceImageBase64({commit}, data) {
            commit('setNewFrontSourceImageBase64', data);
        },
        setFrontSourceImageUrl({commit}, url) {
            commit('setFrontSourceImageUrl', url);
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
    },
};



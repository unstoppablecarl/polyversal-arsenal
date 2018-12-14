import toDataURL from '../../lib/image-to-base64';

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
    },
};



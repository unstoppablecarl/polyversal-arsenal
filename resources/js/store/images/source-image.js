import toDataURL from '../../lib/image-to-base64';
import svgToPngBase64 from '../../lib/svg-to-png-base64';
import svgToString from '../../lib/svg-to-string';
import svgToBase64 from '../../lib/svg-to-base64';
import {notificationFromErrorResponse, notificationSuccess} from '../notification';

export default function makeSourceImageStore({svgId, fileNamePrefix, serverUpdate, serverDelete}) {
    return {
        namespaced: true,
        state() {
            return {
                image_url: null,
                image_base64: null,
                uploading: false,
                deleting: false,
                unsavedChanges: false,
            }
        },
        mutations: {
            setSourceImageBase64(state, value) {
                state.image_base64 = value;
            },
            setSourceImageUrl(state, url) {
                state.image_base64 = null;
                state.image_url    = url;
            },
        },
        actions: {
            setSourceImageUrl({commit}, url) {
                commit('setSourceImageUrl', url);
            },
            loadSourceImageBase64FromUrl({state, commit}) {
                let imageBase64 = state.image_base64;
                let imageUrl    = state.image_url;

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
            getSvgBase64({dispatch, commit}) {
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

            saveSourceImage({state, dispatch, rootState}, newImageData) {

                const handleError = (error) => {
                    console.error(error);
                    notificationFromErrorResponse(error.response);
                };
                state.uploading   = true;
                return serverUpdate(rootState.tile.id, newImageData)
                    .then((response) => {
                        notificationSuccess({
                            title: 'Tile Background Image Updated',
                        });
                        let payload = response.data;
                        return dispatch('setSourceImageUrl', payload.source_image_url);
                    })
                    .then(() => {
                        state.unsavedChanges = true;
                    })
                    .catch(handleError)
                    .finally(() => {
                        state.uploading = false;
                    });
            },
            deleteSourceImage({state, dispatch, rootState}) {

                const handleError = (error) => {
                    console.error(error);
                    notificationFromErrorResponse(error.response);
                };
                state.deleting    = true;
                return serverDelete(rootState.tile.id)
                    .then((response) => {
                        notificationSuccess({
                            title: 'Tile Background Image Deleted',
                        });
                        return dispatch('setSourceImageUrl', null);
                    })
                    .then(() => {
                        state.unsavedChanges = true;
                    })
                    .catch(handleError)
                    .finally(() => {
                        state.deleting = false;
                    });
            },
        },
        getters: {
            sourceImageUrl(state) {
                if (state.image_base64) {
                    return state.image_base64;
                }
                return state.image_url;
            },
            fileName(state, getters, rootState) {
                return fileNamePrefix + rootState.tile.name.replace(/[^a-z0-9]/gi, '_').toLowerCase();
            },
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
            syncing(state) {
                return state.uploading || state.deleting;
            },
            unsavedChanges(state) {
                return state.unsavedChanges;
            },

        },
    };
}

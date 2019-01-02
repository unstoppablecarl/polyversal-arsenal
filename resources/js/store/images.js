import SourceImage from './images/source-image';
import server from './server-repo';

export default {
    namespaced: true,

    state: {},

    actions: {},
    getters: {

    },
    modules: {
        front: SourceImage({
            svgId: 'tile-front-svg',
            fileNamePrefix: 'polyversal-tile-front-',
            serverUpdate: server.frontSourceImageUpdate,
            serverDelete: server.frontSourceImageDelete,
        }),
        back: SourceImage({
            svgId: 'tile-back-svg',
            fileNamePrefix: 'polyversal-tile-back-',
            serverUpdate: server.backSourceImageUpdate,
            serverDelete: server.backSourceImageDelete,
        }),
    },
};


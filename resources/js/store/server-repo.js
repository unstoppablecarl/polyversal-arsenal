import axios from 'axios';

function fetch(tileId) {
    return axios.get('/tiles/' + tileId);
}

function create(data) {
    return axios.post('/tiles', data);
}

function update(data) {
    const url = updateURL(data.id);
    return axios.put(url, data);
}

function frontSourceImageUpdate(tileId, data) {
    const url = frontSourceImageUpdateURL(tileId);
    return axios.put(url, {source_image: data});
}

function backSourceImageUpdate(tileId, data) {
    const url = backSourceImageUpdateURL(tileId);
    return axios.put(url, {source_image: data});
}

function frontSourceImageDelete(tileId) {
    const url = frontSourceImageDeleteURL(tileId);
    return axios.delete(url);
}

function backSourceImageDelete(tileId) {
    const url = backSourceImageDeleteURL(tileId);
    return axios.delete(url);
}

const updateURL                 = (tileId) => '/tiles/' + tileId;
const editURL                   = (tileId) => '/app#/tile/' + tileId;
const viewURL                   = (tileId) => '/tiles/' + tileId;
const deleteURL                 = (tileId) => '/tiles/' + tileId + '/delete';
const frontSourceImageUpdateURL = (tileId) => '/tiles/' + tileId + '/front-source-image/update';
const frontSourceImageDeleteURL = (tileId) => '/tiles/' + tileId + '/front-source-image/delete';
const backSourceImageUpdateURL  = (tileId) => '/tiles/' + tileId + '/back-source-image/update';
const backSourceImageDeleteURL  = (tileId) => '/tiles/' + tileId + '/back-source-image/delete';

export default {
    fetch,
    create,
    update,

    frontSourceImageUpdate,
    backSourceImageUpdate,

    frontSourceImageDelete,
    backSourceImageDelete,

    viewURL,
    editURL,
    deleteURL,
};

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

function updateImage(tileId, imageData) {
    const url = updateImageURL(tileId);
    return axios.post(url, {new_image_data: imageData});
}

function updateURL(tileId) {
    return '/tiles/' + tileId;
}

function updateImageURL(tileId) {
    return '/tiles/' + tileId + '/upload-image';
}


function editURL(tileId) {
    return '/app#/tile/' + tileId;
}

function viewURL(tileId) {
    return '/tiles/' + tileId;
}

function deleteURL(tileId) {
    return '/tiles/' + tileId + '/delete';
}

export default {
    fetch,
    create,
    update,
    updateImage,
    editURL,
    updateURL,
    viewURL,
    deleteURL,
    updateImageURL,
};

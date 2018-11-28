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


function updateURL(tileId) {
    return '/tiles/' + tileId;
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
    editURL,
    updateURL,
    viewURL,
    deleteURL,
};

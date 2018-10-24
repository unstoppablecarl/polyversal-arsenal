import './bootstrap';

import Vuex from 'vuex';
import Vue from 'vue';


import Store from './store';
import Server from './lib/fake-server';
import WeaponGrid from './components/weapon-grid';


let server = Server({
    responseDelay: 100
});

let store = Store(server);

let app = new Vue({
    el: '#app',
    store: store,
    components: {
        WeaponGrid
    },
});

import {ARC_180, ARC_UP} from './data/constants';

//example.weapons.forEach(function (item) {
//    server.create(item);
//});
server.create({
    id: 1,
    display_order: 0,
    quantity: 1,
    slug: 'ama-4',
    arc: 'UP_90',
    tile_weapon_type_id: 3
});

server.create({
    id: 2,
    display_order: 1,
    quantity: 2,
    slug: 'autocannon-1',
    arc: 'RIGHT_90',
});

window.server = server;
window.app = app;
app.$store.dispatch('fetch');

import './bootstrap';

import Vue from 'vue';

import Store from './store';
import Server from './lib/fake-server';
import Tile from './components/tile';

let server = Server({
    responseDelay: 100,
});

let store = Store(server);

let app = new Vue({
    el: '#app',
    store: store,
    components: {
        Tile,
    },
});

let weapon = {
    id: 1,
    display_order: 0,
    quantity: 1,
    slug: 'ama-4',
    arc: 'UP_90',
    tile_weapon_type_id: 3,
};
store.dispatch('weaponCreate', {weapon});

weapon = {
    id: 1,
    display_order: 0,
    quantity: 1,
    slug: 'autocannon-1',
    arc: 'RIGHT_90',
    tile_weapon_type_id: 3,
};
store.dispatch('weaponCreate', {weapon});


weapon = {
    id: 1,
    display_order: 0,
    quantity: 1,
    slug: 'howitzer-1',
    arc: 'RIGHT_90',
    tile_weapon_type_id: 3,
};
store.dispatch('weaponCreate', {weapon});

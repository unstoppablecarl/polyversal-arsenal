import './bootstrap';

import Vue from 'vue';

import Store from './store';
import Server from './lib/fake-server';
import Tile from './components/tile';
import WeaponGrid from './components/weapon-grid';
let server = Server({
    responseDelay: 100,
});

let store = Store(server);

let app = new Vue({
    el: '#app',
    store: store,
    components: {
        Tile,
        WeaponGrid
    },
});

let weapon = {
    id: 1,
    display_order: 0,
    quantity: 3,
    weapon_id: 28,
    arc_direction: 'UP',
    arc_size: 90,
    tile_weapon_type_id: 1,
};
store.dispatch('weaponCreate', {weapon});

//let weapon = {
//    id: 1,
//    weapon_id: 1,
//    display_order: 0,
//    quantity: 1,
//    arc: 'RIGHT_90',
//    tile_weapon_type_id: 3,
//};
//store.dispatch('weaponCreate', {weapon});
//
//
//weapon = {
//    id: 1,
//    weapon_id: 22,
//    display_order: 0,
//    quantity: 1,
//    arc: 'RIGHT_90',
//    tile_weapon_type_id: 3,
//};
//store.dispatch('weaponCreate', {weapon});

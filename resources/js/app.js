import './bootstrap';

import Vue from 'vue';
import VueRouter from 'vue-router';
import store from './store';
import Tile from './components/tile';
import TabInfo from './components/tabs-info';
import TabStats from './components/tabs-stats';
import TabAbilities from './components/tabs-abilities';
import TabWeapons from './components/tabs-weapons';
import WeaponGrid from './components/weapon-grid';
import {tileWeaponCreate} from './store/models/tile-weapon';
 import axios from 'axios';

const routes = [
    {
        path: '/',
        redirect: '/tile/create/stats',
    },
    {
        path: '/tile/:id',
        component: Tile,
        name: 'tile',
        redirect: {name: 'tile-stats'},

        children: [
            {
                name: 'tile-info',
                path: 'info',
                component: TabInfo,
            },
            {
                name: 'tile-stats',
                path: 'stats',
                component: TabStats,
            },
            {
                name: 'tile-abilities',
                path: 'abilities',
                component: TabAbilities,
            },
            {
                name: 'tile-weapons',
                path: 'weapons',
                component: TabWeapons,
            },
        ],
    },
];


if (document.getElementById('app-tile')) {

    const router = new VueRouter({
        routes,
    });
    let app      = new Vue({
        el: '#app-tile',
        store,
        router,
        components: {
            Tile,
            WeaponGrid,
        },
    });
}

import TileGrid from './components/tile-grid';

if (document.getElementById('app-tile-grid')) {

    let app = new Vue({
        el: '#app-tile-grid',
        components: {
            TileGrid,
        },
    });
}
//
//axios.get('/img/damage-weapon.svg'). then(response => {
//    console.log(response.data);
//
//    var parser = new DOMParser();
//    var ajaxdoc = parser.parseFromString( response.data, "image/svg+xml" );
//    var el = ajaxdoc.getElementsByTagName('svg')[0];
//
//    console.log(el);
//
//    //document.getElementsByTagName('svg')[0].appendChild(  );
//
//});

//router.push({name: 'tile-stats', params: {id: 'create'}});


//let weapon = {
//    id: 1,
//    display_order: 0,
//    quantity: 3,
//    weapon_id: 28,
//    arc_direction: 'UP',
//    arc_size: 90,
//    tile_weapon_type_id: 1,
//};
//store.dispatch('weaponCreate', {weapon});

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

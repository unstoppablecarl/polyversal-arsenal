import './bootstrap';

import Vue from 'vue';
import VueRouter from 'vue-router';
import store from './store';
import Tile from './components/tile';
import TabStats from './components/tabs-stats';
import TabAbilities from './components/tabs-abilities';
import TabWeapons from './components/tabs-weapons';
import WeaponGrid from './components/weapon-grid';
import {tileWeaponCreate} from './store/models/tile-weapon';

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


    //let tileData = null
    //if(window.APP_DATA && window.APP_DATA.tile_data){
    //    tileData = window.APP_DATA.tile_data;
    //}
    //
    //let tile_weapons = [];
    //
    //if(tileData.tile_weapons){
    //    tile_weapons = tileData.tile_weapons.map((item) => tileWeaponCreate(item));
    //}

    const router = new VueRouter({
        routes,
    });
    let app = new Vue({
        el: '#app-tile',
        store,
        router,
        components: {
            Tile,
            WeaponGrid,
        },
    });
}

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

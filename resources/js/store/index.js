import Vuex from 'vuex';

import {
    createItem,
    updateItem,
    deleteItem,
    moveItem,
    copyItem,
} from '../lib/collection-helper';

import tileWeapon from './models/tile-weapon';

export default function (server) {
    return new Vuex.Store({
        state: {
            weapons: [],
        },
        mutations: {
            weaponCreate(state, {weapon, newIndex}) {
                weapon = tileWeapon(weapon);
                createItem(state.weapons, weapon, newIndex);
            },
            weaponUpdate(state, weapon) {
                updateItem(state.weapons, weapon);
            },
            weaponCopy(state, weapon) {
                copyItem(state.weapons, weapon);
            },
            weaponDelete(state, weapon) {
                deleteItem(state.weapons, weapon);
            },
            weaponMove(state, {weapon, newIndex}) {
                moveItem(state.weapons, weapon, newIndex);
            },
            weaponsClear(state) {
                state.weapons = [];
            },
        },
        actions: {
            weaponCreate({commit, state}, {weapon, newIndex}) {
                commit('weaponCreate', {weapon, newIndex});
            },
            weaponUpdate({commit}, weapon) {
                commit('weaponUpdate', weapon);
            },
            weaponCopy({commit}, weapon) {
                commit('weaponCopy', weapon);
            },
            weaponDelete({commit}, weapon) {
                commit('weaponDelete', weapon);
            },
            weaponMove({commit, state}, {weapon, newIndex}) {
                commit('weaponMove', {weapon, newIndex});
            },
        },
        getters: {
            weapons(state) {
                return state.weapons;
            },
        },
    });

}


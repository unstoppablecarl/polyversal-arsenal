import Vuex from 'vuex';

import {
    updateItem,
    deleteItem,
    moveItem,
} from '../lib/collection-helper';

export default function (server) {

    return new Vuex.Store({
        state: {
            weapons: [],
        },
        mutations: {
            weaponCreate(state, weapon) {
                state.weapons.push(weapon);
            },
            weaponUpdate(state, weapon) {
                updateItem(state.weapons, weapon);
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
            asyncState(state, value) {
                state.asyncState = value;
            },
        },
        actions: {
            weaponCreate({commit, state}, weapon) {
                commit('asyncState', 'creating');

                return server.create(weapon)
                    .then(function (serverweapon) {

                        commit('asyncState', null);
                        commit('weaponCreate', serverweapon);
                        return serverWeapon;
                    });
            },
            weaponUpdate({commit}, weapon) {
                commit('asyncState', 'updating');

                return server.update(weapon)
                    .then(function (serverweapon) {

                        commit('asyncState', null);
                        commit('weaponUpdate', serverweapon);

                    });
            },
            weaponCopy({commit}, weapon) {
                commit('asyncState', 'updating');

                return server.update(weapon)
                    .then(function (serverweapon) {

                        commit('asyncState', null);
                        commit('weaponUpdate', serverweapon);

                    });
            },
            weaponDelete({commit}, weapon) {
                commit('asyncState', 'deleting');

                return server.delete(weapon)
                    .then(function (serverweapon) {

                        commit('asyncState', null);
                        commit('weaponDelete', serverweapon);

                    });
            },
            weaponMove({commit, state}, {weapon, newIndex}) {
                weapon.display_order = newIndex;

                commit('weaponMove', {weapon, newIndex});

                state.weapons.forEach((item, index) => {
                    item.display_order = index;
                    commit('weaponUpdate', item);
                });
                //
                //return server.update(weapon)
                //    .then((serverWeapon) => {
                //        commit('weaponMove', {serverWeapon, newIndex});
                //
                //        state.weapons.forEach((item, index) => {
                //            item.display_order = index;
                //            commit('weaponUpdate', item);
                //        });
                //    });
            },
            fetch({commit}) {
                commit('asyncState', 'fetching');

                return server.fetch()
                    .then(function (weapons) {

                        commit('asyncState', null);
                        weapons.forEach(function (weapon) {
                            commit('weaponCreate', weapon);
                        });

                    });
            },
            sync({commit, state}) {

                return server.sync(state.weapons)
                    .then(function (weapons) {
                        commit('weaponClear');

                        weapons.forEach(function (weapon) {
                            commit('weaponCreate', weapon);
                        });
                    });
            },
        },
        getters: {
            weapons(state) {
                return state.weapons;
            },
            asyncState(state) {
                return state.asyncState;
            },
        },
    });

}


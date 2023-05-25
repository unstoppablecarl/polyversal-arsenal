import {make as makeTileWeapon, sanitize as sanitizeTileWeapon} from './models/tile-weapon';
import {copyItem, createItem, deleteItem, moveItem, updateItem} from '../lib/collection-helper';
import Weapons from '../data/weapons';
import {TILE_TYPE_BUILDING_ID} from '../data/constants';

export default {
    namespaced: true,
    state() {
        return {
            tile_weapons: [],
        }
    },
    mutations: {
        set(state, tileWeapons) {
            state.tile_weapons = tileWeapons.map((tileWeapon) => {
                return makeTileWeapon(tileWeapon);
            });
        },
        create(state, weapon) {
            weapon = makeTileWeapon(weapon);
            createItem(state.tile_weapons, weapon);
        },
        update(state, weapon) {
            weapon = sanitizeTileWeapon(weapon);
            updateItem(state.tile_weapons, weapon);
        },
        copy(state, weapon) {
            copyItem(state.tile_weapons, weapon);
        },
        delete(state, weapon) {
            deleteItem(state.tile_weapons, weapon);
        },
        move(state, {weapon, newIndex}) {
            moveItem(state.tile_weapons, weapon, newIndex);
        },
        clear(state) {
            state.tile_weapons = [];
        },
    },
    actions: {
        set({commit, state, dispatch}, weapons) {
            commit('set', weapons);
        },
        updateAll({commit, state}, weapons) {
            weapons.forEach((weapon) => {
                commit('update', weapon);
            });
        },
        create({commit}, {weaponId, tileWeaponTypeId}) {
            commit('create', {
                weapon_id: weaponId,
                tile_weapon_type_id: tileWeaponTypeId,
            });
        },
        update({commit, state, getters}, weapon) {
            commit('update', weapon);
        },
        copy({commit}, weapon) {
            commit('copy', weapon);
        },
        delete({commit}, weapon) {
            commit('delete', weapon);
        },
        move({commit, state}, {weapon, newIndex}) {
            commit('move', {weapon, newIndex});
        },
        clear({commit, state}) {
            commit('clear');
        },
    },
    getters: {
        options(state, getters) {
            return getters.weaponRepo.all();
        },
        tile_weapons(state, getters, rootState) {
            return state.tile_weapons.map((tileWeapon) => {

                let cost = getters.getWeaponCost(
                    tileWeapon.weapon_id,
                    tileWeapon.arc_size_id,
                    tileWeapon.tile_weapon_type_id,
                );

                let total_cost = cost * tileWeapon.quantity;
                let weapon = getters.weaponRepo.get(tileWeapon.weapon_id, tileWeapon.tile_weapon_type_id);

                let isBuilding = rootState.tile.tile_type_id == TILE_TYPE_BUILDING_ID
                if (isBuilding) {
                    cost = 0
                    total_cost = 0
                }

                return Object.assign({}, tileWeapon, {weapon, cost, total_cost});
            });
        },
        totalCost(state, getters, rootState) {

            let isBuilding = rootState.tile.tile_type_id == TILE_TYPE_BUILDING_ID
            if (isBuilding) {
                return 0
            }

            let total = 0;
            state.tile_weapons.forEach((tileWeapon) => {
                let cost = getters.getWeaponCost(
                    tileWeapon.weapon_id,
                    tileWeapon.arc_size_id,
                    tileWeapon.tile_weapon_type_id,
                );
                total += cost * tileWeapon.quantity;
            });
            return total;
        },
        weaponRepo(state, getters, rootState) {
            return Weapons(rootState.tile.tile_type_id);
        },
        getWeaponCost(state, getters, rootState) {
            return (weaponId, arcSizeId, weaponTypeId) => {
                return getters.weaponRepo.cost(rootState.tile.targeting_id, weaponId, arcSizeId, weaponTypeId);
            };
        },
        getWeapon(state, getters) {
            return (weaponId, weaponTypeId = 1) => {
                return getters.weaponRepo.get(weaponId, weaponTypeId);
            };
        },
        firstValidWeaponId(state, getters) {
            return getters.weaponRepo.firstId();
        },
    },
};

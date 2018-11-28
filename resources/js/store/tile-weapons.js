import {abilityOptions, amaById} from '../data/options';
import {ABILITY_JUMP_JETS_ID, TILE_TYPE_VEHICLE_ID} from '../data/constants';
import {abilityCost, abilityValid} from '../data/abilities';
import {make as makeTileWeapon, sanitize as sanitizeTileWeapon} from './models/tile-weapon';
import {copyItem, createItem, deleteItem, moveItem, updateItem} from '../lib/collection-helper';
import Weapons from '../data/weapons';

export default {
    namespaced: true,
    state: {
        tile_weapons: [],
    },
    mutations: {
        set(state, tileWeapons) {
            state.tile_weapons = tileWeapons.map((tileWeapon) => {
                return makeTileWeapon(tileWeapon);
            });
        },
        create(state, {weapon, newIndex}) {
            weapon = makeTileWeapon(weapon);
            createItem(state.tile_weapons, weapon, newIndex);
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
        clear(state){
            state.tile_weapons = [];
        }
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
        create({commit}, {weapon, newIndex}) {
            commit('create', {weapon, newIndex});
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
        clear({commit, state}){
            commit('clear');
        }
    },
    getters: {
        options(state, getters) {
            return getters.weaponRepo.all();
        },
        tile_weapons(state, getters) {
            return state.tile_weapons.map((tileWeapon) => {

                let cost = getters.getWeaponCost(
                    tileWeapon.weapon_id,
                    tileWeapon.arc_size_id,
                    tileWeapon.tile_weapon_type_id,
                );

                let total_cost = cost * tileWeapon.quantity;
                let weapon     = getters.weaponRepo.get(tileWeapon.weapon_id, tileWeapon.tile_weapon_type_id);

                return Object.assign({}, tileWeapon, {weapon, cost, total_cost});
            });
        },
        totalCost(state, getters) {
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
        warheadWeaponsTotalCost(state, getters) {
            let total = 0;
            state.tile_weapons.forEach((tileWeapon) => {
                let weaponItem = getters.weaponRepo.get(tileWeapon.weapon_id);
                if (weaponItem.has_warheads) {
                    let cost = getters.getWeaponCost(
                        tileWeapon.weapon_id,
                        tileWeapon.arc_size_id,
                        tileWeapon.tile_weapon_type_id,
                    );
                    total += cost * tileWeapon.quantity;
                }
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

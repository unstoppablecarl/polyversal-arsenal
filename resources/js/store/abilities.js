import {abilityOptions, amaById} from '../data/options';
import {ABILITY_JUMP_JETS_ID, TILE_TYPE_VEHICLE_ID} from '../data/constants';
import {abilityCost, abilityValid, hasDefensiveAbility} from '../data/abilities';

export default {
    namespaced: true,
    state: {
        ability_ids: [],
    },
    mutations: {
        set(state, abilityIds) {
            state.ability_ids = abilityIds;
        },
        add(state, value) {
            if (state.ability_ids.indexOf(value) === -1) {
                state.ability_ids.push(value);
            }
        },
        remove(state, value) {
            let index = state.ability_ids.indexOf(value);
            if (index !== -1) {
                state.ability_ids.splice(index, 1);
            }
        },
    },
    actions: {
        set({commit, state}, abilityIds) {
            commit('set', abilityIds);
        },
        add({commit, state}, abilityId) {
            commit('add', abilityId);
        },
        remove({commit, state}, abilityId) {
            commit('remove', abilityId);
        },
        removeInvalid({commit, state, rootState}, newTileTypeId) {
            state.ability_ids.forEach((abilityId) => {
                if (!abilityValid(abilityId, newTileTypeId)) {
                    commit('remove', abilityId);
                }
            });
        },
    },
    getters: {
        ability_ids(state) {
            return state.ability_ids;
        },
        abilityList(state) {
            return abilityOptions
                .filter((item) => {
                    let active = state.ability_ids.indexOf(item.id) !== -1;
                    return active;
                })
                .map((item) => item.display_name);
        },
        options(state, getters, rootState) {
            let isVehicle = rootState.tile.tile_type_id == TILE_TYPE_VEHICLE_ID;

            return abilityOptions

                .map((item) => {
                    let active = state.ability_ids.indexOf(item.id) !== -1;
                    let valid  = getters.isValid(item.id);
                    let cost   = false;
                    if (valid) {
                        cost = getters.cost(item.id);
                    }

                    return {
                        id: item.id,
                        display_name: item.display_name,
                        valid,
                        active,
                        cost,
                        is_defensive: item.is_defensive && isVehicle,
                        is_jumpjet:  item.is_jumpjet && isVehicle,
                    };
                });
        },
        canAdd(state, getters) {
            return (abilityId) => {

                let hasAbility = state.ability_ids.indexOf(abilityId) !== -1;
                if (hasAbility) {
                    return false;
                }
                return getters.isValid(abilityId);
            };
        },
        isValid(state, getters, rootState) {
            return (abilityId) => {
                return abilityValid(abilityId, rootState.tile.tile_type_id);
            };
        },
        cost(state, getters, rootState, rootGetters) {
            return (abilityId) => {
                return abilityCost(abilityId, rootState.tile.tile_type_id, rootState.tile.tile_class_id, rootGetters['tile_weapons/tile_weapons']);
            };
        },
        hasJumpJets(state) {
            return state.ability_ids.indexOf(ABILITY_JUMP_JETS_ID) != -1;
        },
        hasDefensiveAbility(state) {
            return hasDefensiveAbility(state.ability_ids);
        },
        totalCost(state, getters, rootState, rootGetters) {
            let sum           = 0;
            const tileTypeId  = rootState.tile.tile_type_id;
            const tileClassId = rootState.tile.tile_class_id;
            const tileWeapons  = rootGetters['tile_weapons/tile_weapons'];

            state.ability_ids.forEach((abilityId) => {
                sum += abilityCost(abilityId, tileTypeId, tileClassId, tileWeapons);
            });

            let amaCost = amaById[rootState.tile.anti_missile_system_id].cost;

            return sum +
                rootState.tile.stealth +
                amaCost;
        },
    },
};

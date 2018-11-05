import Vuex from 'vuex';

import {copyItem, createItem, deleteItem, moveItem, updateItem} from '../lib/collection-helper';

import {tileWeaponCreate, tileWeaponUpdate} from './models/tile-weapon';
import {
    ABILITY_JUMP_JETS_ID,
    AMA_NONE_ID,
    TECH_LEVEL_TYPICAL_ID,
    TILE_TYPE_INFANTRY_ID,
    TILE_TYPE_VEHICLE_ID,
} from '../data/constants';
import {getChassis} from '../data/chassis';
import {mobilityById, mobilityOptionsByTileTypeId} from '../data/options-mobility';
import {armorOptionsByTileTypeId} from '../data/options-armor';
import {
    amaById,
    techLevelById,
    techLevelOptions,
    tileTypeById,
    vehicleClassById,
    abilityOptions,
} from '../data/options';
import {abilityCost, abilityValid, hasDefensiveAbility} from '../data/abilities';
import Weapons from '../data/weapons';
import {getStealthOptions} from '../data/options-stealth';

export default function (server) {
    return new Vuex.Store({
        state: {
            weapons: [],
            tile: {
                name: 'New Tile Name',
                app_version: 1,
                type_id: TILE_TYPE_VEHICLE_ID,
                class_id: 3,
                armor: 3,
                tech_level_id: 2,
                mobility_id: 9,
                targeting_id: 1,
                assault_id: 1,
                stealth: 0,
                ama_id: 3,
            },
            abilityIds: [],
        },
        mutations: {
            weaponCreate(state, {weapon, newIndex}) {
                weapon = tileWeaponCreate(weapon);
                createItem(state.weapons, weapon, newIndex);
            },
            weaponUpdate(state, weapon) {
                weapon = tileWeaponUpdate(weapon);
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
            updateTile(state, value) {
                state.tile = value;
            },
            setAbilityIds(state, abilityIds) {
                state.abilityIds = abilityIds;
            },
            addAbility(state, value) {
                if (state.abilityIds.indexOf(value) === -1) {
                    state.abilityIds.push(value);
                }
            },
            removeAbility(state, value) {
                let index = state.abilityIds.indexOf(value);
                if (index !== -1) {
                    state.abilityIds.splice(index, 1);
                }
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
            weaponClear({commit, state}) {
                commit('weaponsClear');
            },
            setTileTargetingId({commit, state}, value) {
                commit('setTileTargetingId', value);
            },
            updateTile({commit, state, getters}, tile) {

                if (tile.type_id !== undefined) {
                    tile.mobility_id = firstMobilityIdForType(tile.type_id);

                    let toVehicle   = tile.type_id === TILE_TYPE_VEHICLE_ID;
                    let fromVehicle = state.tile.type_id === TILE_TYPE_VEHICLE_ID;

                    if (!toVehicle) {
                        tile.class_id = 1;
                        tile.ama_id   = AMA_NONE_ID;
                    }

                    if (fromVehicle || toVehicle) {
                        commit('weaponsClear');
                    }

                    let armor = tile.armor;

                    if (armor === undefined) {
                        armor = state.tile.armor;
                    }
                    let max = maxArmorForType(tile.type_id);
                    if (armor > max) {
                        armor = max;
                    }

                    let abilityIds = state.abilityIds.filter((abilityId) => {
                        return abilityValid(abilityId, tile.type_id);
                    });
                    commit('setAbilityIds', abilityIds);
                }

                tile = Object.assign({}, state.tile, tile);

                commit('updateTile', tile);
            },
            setAbilityIds({commit, state}, abilityIds) {
                commit('setAbilityIds', abilityIds);
            },
            addAbility({commit, state}, abilityId) {
                commit('addAbility', abilityId);
            },
            removeAbility({commit, state}, abilityId) {
                commit('removeAbility', abilityId);
            },
        },
        getters: {
            abilityIds(state) {
                return state.abilityIds;
            },
            tile(state) {
                return state.tile;
            },
            weapons(state) {
                return state.weapons;
            },
            amaCost(state) {
                return amaById[state.tile.ama_id].cost;
            },
            chassis(state) {
                return getChassis(state.tile);
            },
            getChassis(state) {
                return (settings) => {
                    settings = Object.assign({}, state.tile, settings);
                    return getChassis(settings);
                };
            },
            statsTotalCost(state, getters) {
                let chassis     = getters.chassis;
                let chassisCost = 0;
                if (chassis) {
                    chassisCost = chassis.cost;
                }
                return chassisCost +
                    state.tile.armor +
                    state.tile.targeting_id +
                    state.tile.assault_id;
            },
            abilitiesTotalCost(state, getters) {
                let sum = 0;
                state.abilityIds.forEach((id) => {
                    sum += getters.abilityCost(id);
                });

                return sum +
                    state.tile.stealth +
                    state.tile.ama_id;
            },
            weaponsTotalCost(state, getters) {
                let total = 0;
                state.weapons.forEach((tileWeapon) => {
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
                state.weapons.forEach((tileWeapon) => {
                    let weaponItem = getters.weaponOptions.get(tileWeapon.weapon_id);
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
            totalCost(state, getters) {
                return getters.statsTotalCost +
                    getters.abilitiesTotalCost +
                    getters.weaponsTotalCost;
            },
            hasTileClass(state) {
                return state.tile.type_id == TILE_TYPE_VEHICLE_ID;
            },
            hasJumpJets(state) {
                return state.abilityIds.indexOf(ABILITY_JUMP_JETS_ID) != -1;
            },
            hasDefensiveSystems(state) {
                if (state.tile.type_id === TILE_TYPE_INFANTRY_ID) {
                    return false;
                }
                if (state.tile.type_id === TILE_TYPE_VEHICLE_ID) {
                    if (state.tile.stealth) {
                        return true;
                    }
                }
                if (state.tile.ama_id) {
                    return true;
                }
                return hasDefensiveAbility(state.abilityIds);
            },
            techLevelOptions(state, getters) {
                return makeOptions(techLevelOptions, (id) => {
                    return getters.getTypicalChassisMod({
                        tech_level_id: id,
                    });
                });
            },
            mobilityOptions(state, getters) {
                let options = mobilityOptionsByTileTypeId[state.tile.type_id];
                return makeOptions(options, (id) => {
                    return getters.getChassis({
                        mobility_id: id,
                        tech_level_id: TECH_LEVEL_TYPICAL_ID,
                        armor: 0,
                    });
                });
            },
            armorOptions(state, getters) {
                let armorOptions = armorOptionsByTileTypeId[state.tile.type_id];

                return makeOptions(armorOptions, (tile_armor) => {
                    return getters.getZeroArmorChassisMod(tile_armor);
                });
            },
            stealthOptions(state) {
                return getStealthOptions(state.tile.type_id, state.tile.class_id);
            },
            abilityOptions(state, getters) {
                return abilityOptions

                    .map((item) => {
                        let active = getters.abilityIds.indexOf(item.id) !== -1;
                        let valid  = getters.abilityValid(item.id);
                        let cost   = false;
                        if (valid) {
                            cost = getters.abilityCost(item.id);
                        }

                        let isVehicle = state.tile.type_id == TILE_TYPE_VEHICLE_ID;

                        return {
                            id: item.id,
                            display_name: item.display_name,
                            valid,
                            active,
                            cost,
                            is_defensive: item.is_defensive && isVehicle,
                        };
                    });
            },
            getZeroArmorChassisMod(state, getters) {
                return (armor) => {
                    let current = getters.getChassis({
                        armor: 0,
                    });
                    let result  = getters.getChassis({armor});
                    let move    = result.move - current.move;
                    let evasion = result.evasion - current.evasion;
                    return {
                        id: result.id,
                        cost: armor,
                        move: move,
                        evasion: evasion,
                    };
                };
            },
            typicalChassis(state, getters) {
                return getters.getChassis({
                    mobility_id: state.tile.mobility_id,
                    tech_level_id: TECH_LEVEL_TYPICAL_ID,
                });
            },
            getTypicalChassisMod(state, getters) {
                return (settings) => {

                    let typical = getters.typicalChassis;
                    let result  = getters.getChassis(settings);
                    let cost    = result.cost - typical.cost;
                    let move    = result.move - typical.move;
                    let evasion = result.evasion - typical.evasion;
                    return {
                        id: result.id,
                        cost: cost,
                        move: move,
                        evasion: evasion,
                    };
                };
            },

            canAddAbility(state, getters) {
                return (abilityId) => {

                    let hasAbility = getters.abilityIds.indexOf(abilityId) !== -1;
                    if (hasAbility) {
                        return false;
                    }
                    return abilityValid(abilityId, state.tile.type_id);
                };
            },
            abilityValid(state) {
                return (abilityId) => {
                    return abilityValid(abilityId, state.tile.type_id);
                };
            },
            abilityCost(state, getters) {
                return (abilityId) => {
                    return abilityCost(abilityId, state.tile.type_id, state.tile.class_id, getters.warheadWeaponsTotalCost);
                };
            },
            getWeaponCost(state, getters) {
                return (weaponId, arcSizeId, weaponTypeId) => {
                    return getters.weaponOptions.cost(state.tile.targeting_id, weaponId, arcSizeId, weaponTypeId);
                };
            },
            weaponOptions(state, getters) {
                return Weapons(state.tile.type_id);
            },
            subTitle(state) {
                let mobility         = '';
                let classDisplayName = '';
                let techLevel        = '';

                let tile       = state.tile;
                let isTypical  = tile.tech_level_id == TECH_LEVEL_TYPICAL_ID;
                let isVehicle  = tile.type_id == TILE_TYPE_VEHICLE_ID;
                let isInfantry = tile.type_id == TILE_TYPE_INFANTRY_ID;

                if (!isTypical) {
                    techLevel = techLevelById[tile.tech_level_id].display_name;
                }

                if (isVehicle) {
                    classDisplayName = 'Class ' + tile.class_id + ' ' + vehicleClassById[tile.class_id].display_name;
                }

                if (!isInfantry) {
                    mobility = mobilityById[tile.mobility_id].display_name;
                }

                let type  = tileTypeById[tile.type_id].display_name;
                let items = [
                    techLevel,
                    classDisplayName,
                    mobility,
                    type,
                ].filter(s => s);
                return items.join(' ');
            },
        },
    });
}

function maxArmorForType(typeId) {
    let armorOptions = armorOptionsByTileTypeId[typeId];
    let keys         = Object.keys(armorOptions);
    let last         = keys[keys.length - 1];
    return parseInt(last, 10);
}

function firstMobilityIdForType(typeId) {
    let options  = mobilityOptionsByTileTypeId[typeId];
    let firstKey = Object.keys(options)[0];
    return options[firstKey].id;
}

function makeOptions(options, getStats) {
    return options.map((item) => {
        let {id, display_name}    = item;
        let {cost, move, evasion} = getStats(id);
        return {
            id,
            display_name,
            cost,
            move,
            evasion,
        };
    });
}

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
import server from './server-repo';
import {notificationFromErrorResponse, notificationSuccess} from './notification';

export default {
    //namespaced: true,
    state: {
        //tile_weapons: [
        //    {
        //        'id': 'new-1',
        //        'tile_weapon_type_id': 1,
        //        'arc_size_id': 1,
        //        'arc_direction_id': 2,
        //        'quantity': 4,
        //        'display_order': 1,
        //        'weapon_id': 2,
        //    },
        //    {
        //        'id': 'new-2',
        //        'tile_weapon_type_id': 2,
        //        'arc_size_id': 2,
        //        'arc_direction_id': 1,
        //        'quantity': 2,
        //        'display_order': 1,
        //        'weapon_id': 75,
        //    },
        //],
        tile: {
            id: null,
            name: 'New Tile Name',
            tile_type_id: TILE_TYPE_VEHICLE_ID,
            tile_class_id: 3,
            armor: 2,
            tech_level_id: 2,
            mobility_id: 10,
            targeting_id: 1,
            assault_id: 1,
            stealth: 1,
            anti_missile_system_id: 3,
        },
    },
    mutations: {
        set(state, data) {
            let tileData = extractTileData(data);

            Object.assign(state.tile, tileData);

            //state.tile_weapons = data.tile_weapons;
            //state.ability_ids  = data.ability_ids;
        },

        updateTile(state, value) {
            state.tile = value;
        },

    },
    actions: {
        fetch({commit, state}, tileId) {
            return server.fetch(tileId)
                .then((response) => {
                    notificationSuccess({
                        title: 'Tile Loaded',
                    });
                    let data = response.data.data;
                    commit('set', data);
                })
                .catch((error) => {
                    console.error(error);
                    if (error.response.status == 404) {
                        return {
                            not_found: true,
                        };
                    }

                    console.log(error.response.status);
                    if (error.response.status == 401) {
                        return {
                            unauthorized: true,
                        };
                    }
                    notificationFromErrorResponse(error.response);
                });
        },
        save({commit, state, getters}) {
            let data = _.pick(state.tile, [
                'id',
                'name',
                'tile_type_id',
                'tile_class_id',
                'armor',
                'tech_level_id',
                'mobility_id',
                'targeting_id',
                'assault_id',
                'stealth',
                'anti_missile_system_id',
            ]);

            data.tile_weapons = state.tile_weapons;
            //data.ability_ids  = state.ability_ids;

            console.log('d', data.ability_ids);
            data.cost = {
                total: getters.totalCost,
                weapons: getters.weaponsTotalCost,
                chassis: getters.statsTotalCost,
                abilities: getters.abilitiesTotalCost,
            };

            data.cached_cost = getters.totalCost;

            if (!state.tile.id) {
                return server.create(data)
                    .then((response) => {
                        notificationSuccess({
                            title: 'Tile Created',
                        });
                        let data = response.data.data;
                        commit('set', data);
                    })
                    .catch((error) => {

                        console.error(error);
                        notificationFromErrorResponse(error.response);
                    });
            }
            else {
                return server.update(data)
                    .then((response) => {
                        notificationSuccess({
                            title: 'Tile Saved',
                        });
                        let data = response.data.data;
                        commit('set', data);
                    })
                    .catch((error) => {
                        console.error(error);
                        notificationFromErrorResponse(error.response);
                    });
            }
        },
        updateTile({commit, state, getters}, tile) {

            if (tile.tile_type_id !== undefined) {
                tile.mobility_id = firstMobilityIdForType(tile.tile_type_id);

                let toVehicle   = tile.tile_type_id === TILE_TYPE_VEHICLE_ID;
                let fromVehicle = state.tile.tile_type_id === TILE_TYPE_VEHICLE_ID;

                if (!toVehicle) {
                    tile.tile_class_id          = 1;
                    tile.anti_missile_system_id = AMA_NONE_ID;
                }

                if (fromVehicle || toVehicle) {
                    commit('weaponsClear');
                }

                let armor = tile.armor;

                if (armor === undefined) {
                    armor = state.tile.armor;
                }
                let max = maxArmorForType(tile.tile_type_id);
                if (armor > max) {
                    armor = max;
                }


                let abilityIds = state.ability_ids.filter((abilityId) => {
                    return abilityValid(abilityId, tile.tile_type_id);
                });
                commit('setAbilityIds', abilityIds);
            }

            tile = Object.assign({}, state.tile, tile);

            commit('updateTile', tile);
        },

    },
    getters: {

        tile(state) {
            return state.tile;
        },

        amaCost(state) {
            return amaById[state.tile.anti_missile_system_id].cost;
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


        totalCost(state, getters) {
            return getters.statsTotalCost +
                getters.abilitiesTotalCost +
                getters.weaponsTotalCost;
        },
        hasTileClass(state) {
            return state.tile.tile_type_id == TILE_TYPE_VEHICLE_ID;
        },
        hasDefensiveSystems(state) {
            if (state.tile.tile_type_id === TILE_TYPE_INFANTRY_ID) {
                return false;
            }
            if (state.tile.tile_type_id === TILE_TYPE_VEHICLE_ID) {
                if (state.tile.stealth) {
                    return true;
                }
            }
            if (state.tile.anti_missile_system_id) {
                return true;
            }
            return hasDefensiveAbility(state.ability_ids);
        },
        techLevelOptions(state, getters) {
            return makeOptions(techLevelOptions, (id) => {
                return getTypicalChassisDiff({
                    tech_level_id: id,
                });
            });

            function getTypicalChassisDiff(settings) {

                let typical = getters.getChassis({
                    mobility_id: state.tile.mobility_id,
                    tech_level_id: TECH_LEVEL_TYPICAL_ID,
                });
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
            }
        },
        mobilityOptions(state, getters) {
            let options = mobilityOptionsByTileTypeId[state.tile.tile_type_id];
            return makeOptions(options, (id) => {
                return getters.getChassis({
                    mobility_id: id,
                    tech_level_id: TECH_LEVEL_TYPICAL_ID,
                    armor: 0,
                });
            });
        },
        armorOptions(state, getters) {
            let armorOptions = armorOptionsByTileTypeId[state.tile.tile_type_id];

            return makeOptions(armorOptions, (tile_armor) => {
                return getZeroArmorChassisMod(tile_armor);
            });

            function getZeroArmorChassisMod(armor) {
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
            }
        },
        stealthOptions(state) {
            return getStealthOptions(state.tile.tile_type_id, state.tile.tile_class_id);
        },

        viewURL(state) {
            return server.viewURL(state.tile.id);
        },
        editURL(state) {
            return server.editURL(state.tile.id);
        },
        deleteURL(state) {
            return server.deleteURL(state.tile.id);
        },
        subTitle(state) {
            let mobility         = '';
            let classDisplayName = '';
            let techLevel        = '';

            let tile       = state.tile;
            let isTypical  = tile.tech_level_id == TECH_LEVEL_TYPICAL_ID;
            let isVehicle  = tile.tile_type_id == TILE_TYPE_VEHICLE_ID;
            let isInfantry = tile.tile_type_id == TILE_TYPE_INFANTRY_ID;

            if (!isTypical) {
                techLevel = techLevelById[tile.tech_level_id].display_name;
            }

            if (isVehicle) {
                classDisplayName = 'Class ' + tile.tile_class_id + ' ' + vehicleClassById[tile.tile_class_id].display_name;
            }

            if (!isInfantry) {
                mobility = mobilityById[tile.mobility_id].display_name;
            }

            let type  = tileTypeById[tile.tile_type_id].display_name;
            let items = [
                techLevel,
                classDisplayName,
                mobility,
                type,
            ].filter(s => s);
            return items.join(' ');
        },
    },
};

function extractTileData(data) {
    return _.pick(data, [
        'id',
        'name',
        'tile_type_id',
        'tile_class_id',
        'armor',
        'tech_level_id',
        'mobility_id',
        'targeting_id',
        'assault_id',
        'stealth',
        'anti_missile_system_id',
    ]);
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

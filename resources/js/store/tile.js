import {
    AMA_NONE_ID,
    BUILDING_CAPACITY_BY_CLASS,
    COMBAT_VALUE_D4_ID,
    COMBAT_VALUE_NONE_ID,
    TECH_LEVEL_ADVANCED_ID,
    TECH_LEVEL_PRIMITIVE_ID,
    TECH_LEVEL_TYPICAL_ID,
    TILE_TYPE_BUILDING_ID,
    TILE_TYPE_CAVALRY_ID,
    TILE_TYPE_INFANTRY_ID,
    TILE_TYPE_VEHICLE_ID,
} from '../data/constants';
import {getChassis} from '../data/chassis';
import {firstMobilityIdForTileType, mobilityById, mobilityOptionsByTileTypeId} from '../data/options-mobility';
import {getArmorOptions, normalizeArmor} from '../data/options-armor';
import {
    amaById,
    assaultOptions,
    buildingAssaultOptions,
    buildingTargetingOptions,
    targetingById,
    targetingOptions,
    techLevelOptions,
    tileTypeById,
} from '../data/options';
import {getMaxStealth, getStealthOptions} from '../data/options-stealth';

import {sanitize as sanitizeTile} from './models/tile';
import getMaxSize from '../data/combatant-max-sizes';

export default {
    namespaced: true,
    state() {
        return {
            id: null,
            name: 'New Tile Name',
            tile_type_id: TILE_TYPE_BUILDING_ID,
            tile_class_id: 1,
            armor: 0,
            tech_level_id: 2,
            mobility_id: 17,
            targeting_id: 1,
            assault_id: 6,
            stealth: 0,
            anti_missile_system_id: 6,
            flavor_text: '',
            //front_image_url: null,
            //front_thumb_url: null,
            //back_image_url: null,
            //back_thumb_url: null,
        };
    },
    mutations: {
        update(state, data) {
            data = sanitizeTile(data);
            //state.front_image_base_64 = null;
            Object.assign(state, data);
        },
        //setNewFrontImageData(state, data) {
        //    state.front_image_base_64  = null;
        //    state.new_front_image_data = data;
        //},
        //setImageBase64(state, value) {
        //    state.front_image_base_64 = value;
        //},
    },
    actions: {
        update({commit, state, getters, dispatch}, tile) {

            let changed = tile.tile_type_id != state.tile_type_id;
            if (tile.tile_type_id !== undefined && changed) {
                tile.mobility_id = tile.mobility_id || firstMobilityIdForTileType(tile.tile_type_id);

                let toCavalry = tile.tile_type_id === TILE_TYPE_CAVALRY_ID;
                let toInfantry = tile.tile_type_id === TILE_TYPE_INFANTRY_ID;
                let toVehicle = tile.tile_type_id === TILE_TYPE_VEHICLE_ID;
                let fromVehicle = state.tile_type_id === TILE_TYPE_VEHICLE_ID;
                let toBuilding = tile.tile_type_id === TILE_TYPE_BUILDING_ID;
                let fromBuilding = state.tile_type_id === TILE_TYPE_BUILDING_ID;

                if (toInfantry || toCavalry) {
                    tile.tile_class_id = 1;
                    tile.anti_missile_system_id = AMA_NONE_ID;
                }

                if (fromVehicle || toVehicle || fromBuilding || toBuilding) {
                    commit('tile_weapons/clear', null, {root: true});
                }
                let {tile_type_id, tile_class_id, stealth} = Object.assign({}, state, tile);
                let maxStealth = getMaxStealth(tile_type_id, tile_class_id);
                if (stealth > maxStealth) {
                    stealth = maxStealth;
                }
                tile.stealth = stealth;

                if (toBuilding) {
                    tile.assault_id = COMBAT_VALUE_NONE_ID;
                }

                if (fromBuilding) {
                    tile.assault_id = COMBAT_VALUE_D4_ID;
                }
            }

            let {tile_type_id, tile_class_id, armor} = Object.assign({}, state, tile);

            tile.armor = normalizeArmor(tile_type_id, tile_class_id, armor);

            dispatch('abilities/removeInvalid', {newTileTypeId: tile_type_id, tile}, {root: true});

            commit('update', tile);
        },
        //setNewFrontImageData({commit}, data) {
        //    commit('setNewFrontImageData', data);
        //},
        //loadImageBase64({state, commit}) {
        //    if (!state.front_image_base_64) {
        //        return toDataURL(state.front_image_url, 'png')
        //            .then((dataURL) => {
        //                commit('setImageBase64', dataURL);
        //            });
        //    }
        //
        //    return Promise.resolve();
        //},
    },
    getters: {
        tile(state) {
            return state;
        },
        chassis(state) {
            return getChassis(state);
        },
        evasion(state, getters) {
            return getters.chassis.evasion;
        },
        move(state, getters) {
            return getters.chassis.move;
        },
        damageTrack(state, getters) {
            return getters.chassis.damage_track;
        },
        getChassis(state) {
            return (settings) => {
                settings = Object.assign({}, state, settings);
                return getChassis(settings);
            };
        },
        amaCost(state) {
            return amaById[state.anti_missile_system_id].cost;
        },
        statsTotalCost(state, getters) {

            if (state.tile_type_id == TILE_TYPE_BUILDING_ID) {
                return 0;
            }
            let chassis = getters.chassis;
            let chassisCost = 0;
            if (chassis) {
                chassisCost = chassis.cost;
            }
            return chassisCost +
                state.armor +
                state.targeting_id +
                state.assault_id;
        },
        hasTileClass(state) {
            return state.tile_type_id == TILE_TYPE_VEHICLE_ID || state.tile_type_id == TILE_TYPE_BUILDING_ID;
        },
        hasAMAOption(state) {
            return state.tile_type_id == TILE_TYPE_VEHICLE_ID;
        },
        hasDefensiveSystems(state, getters, rootState, rootGetters) {
            const isInfantry = state.tile_type_id === TILE_TYPE_INFANTRY_ID;
            const isVehicle = state.tile_type_id === TILE_TYPE_VEHICLE_ID;

            if (isInfantry) {
                return false;
            }
            if (isVehicle && state.stealth) {
                return true;
            }
            if (state.anti_missile_system_id !== AMA_NONE_ID) {
                return true;
            }

            return rootGetters['abilities/hasDefensiveAbility'];
        },
        techLevelOptions(state, getters) {
            return makeOptions(techLevelOptions, (id) => {
                if (state.tile_type_id === TILE_TYPE_BUILDING_ID) {
                    return {
                        id,
                        cost: 0,
                        move: 0,
                        evasion: 0,
                    };
                }
                return getTypicalChassisDiff({
                    tech_level_id: id,
                });
            });

            function getTypicalChassisDiff(settings) {

                let typical = getters.getChassis({
                    mobility_id: state.mobility_id,
                    tech_level_id: TECH_LEVEL_TYPICAL_ID,
                });
                let result = getters.getChassis(settings);
                let cost = result.cost - typical.cost;
                let move = result.move - typical.move;
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
            let options = mobilityOptionsByTileTypeId[state.tile_type_id];
            return makeOptions(options, (id) => {
                return getters.getChassis({
                    mobility_id: id,
                    tech_level_id: TECH_LEVEL_TYPICAL_ID,
                    armor: 0,
                });
            });
        },
        armorOptions(state, getters) {

            let armorOptions = getArmorOptions(state.tile_type_id, state.tile_class_id);

            return makeOptions(armorOptions, (tile_armor) => {
                if (state.tile_type_id === TILE_TYPE_BUILDING_ID) {
                    return {
                        id: tile_armor,
                        display_name: 'Armor ' + tile_armor,
                        cost: 0,
                        move: 0,
                        evasion: 0,
                    };
                }
                return getZeroArmorChassisMod(tile_armor);
            });

            function getZeroArmorChassisMod(armor) {
                let current = getters.getChassis({
                    armor: 0,
                });
                let result = getters.getChassis({armor});
                let move = result.move - current.move;
                let evasion = result.evasion - current.evasion;
                return {
                    id: result.id,
                    cost: armor,
                    move: move,
                    evasion: evasion,
                };
            }
        },
        hasTechLevel(state) {
            return state.tile_type_id !== TILE_TYPE_BUILDING_ID;
        },
        hasAssaultOptions(state) {
            return state.tile_type_id !== TILE_TYPE_BUILDING_ID;
        },
        hasStealthAbility(state) {
            return state.tile_type_id !== TILE_TYPE_BUILDING_ID;
        },
        stealthOptions(state) {
            return getStealthOptions(state.tile_type_id, state.tile_class_id);
        },
        targetingOptions(state) {
            if (state.tile_type_id === TILE_TYPE_BUILDING_ID) {
                return buildingTargetingOptions;
            }
            return targetingOptions;
        },
        hasAssaultStat(state) {
            return state.tile_type_id !== TILE_TYPE_BUILDING_ID;
        },
        assaultOptions(state) {
            if (state.tile_type_id === TILE_TYPE_BUILDING_ID) {
                return buildingAssaultOptions;
            }
            return assaultOptions;
        },
        isBuilding(state) {
            return state.tile_type_id == TILE_TYPE_BUILDING_ID;
        },
        buildingCapacity(state) {
            if (state.tile_type_id != TILE_TYPE_BUILDING_ID) {
                return 0;
            }

            return BUILDING_CAPACITY_BY_CLASS[state.tile_class_id]
        },
        makeSubtitle(state) {
            return (vehiclePrefix) => {
                let mobility = '';
                let classDisplayName = '';
                let techLevel = '';

                let isPrimitive = state.tech_level_id == TECH_LEVEL_PRIMITIVE_ID;
                let isAdvanced = state.tech_level_id == TECH_LEVEL_ADVANCED_ID;

                let isVehicle = state.tile_type_id == TILE_TYPE_VEHICLE_ID;
                let isBuilding = state.tile_type_id == TILE_TYPE_BUILDING_ID;

                let isInfantry = state.tile_type_id == TILE_TYPE_INFANTRY_ID;

                if (isPrimitive) {
                    techLevel = 'Prim';
                }
                if (isAdvanced) {
                    techLevel = 'Adv';
                }

                if (isVehicle || isBuilding) {
                    classDisplayName = vehiclePrefix + ' ' + state.tile_class_id;
                }

                if (!isInfantry && !isBuilding) {
                    mobility = mobilityById[state.mobility_id].display_name;
                }

                let type = tileTypeById[state.tile_type_id].display_name;
                let items = [
                    techLevel,
                    classDisplayName,
                    mobility,
                    type,
                ].filter(s => s);
                let result = items.join(' ');

                if(isBuilding){
                    result += ', Cap. ' + BUILDING_CAPACITY_BY_CLASS[state.tile_class_id]
                }

                return result
            };
        },
        subTitle(state, getters) {
            return getters.makeSubtitle('Class');
        },
        printSubTitle(state, getters) {
            return getters.makeSubtitle('Cls');
        },
        //new_front_image_data(state) {
        //    return state.new_front_image_data;
        //},
        //imageFrontURL(state) {
        //    if (state.front_image_base_64) {
        //        return state.front_image_base_64;
        //    }
        //    return state.front_image_url;
        //},
        maxSize(state) {
            return getMaxSize(state.tile_type_id, state.tile_class_id);
        },
        isCurrentTargetingDie(state) {
            return (die) => {
                let targeting = targetingById[state.targeting_id];
                if (!targeting) {
                    return false;
                }
                return targeting.name.toLowerCase() === die.toLowerCase();
            };
        },
    },
};

function makeOptions(options, getStats) {
    return options.map((item) => {
        let {id, display_name} = item;
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



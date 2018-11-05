import abilityData from '../../../source-data/static/abilities.json';
import {keyBy, find} from 'lodash';
import {TILE_TYPE_VEHICLE_ID} from './constants';
import {tileTypeById} from './options';

const abilitiesById = keyBy(abilityData, 'id');

function abilityValid(abilityId, tileTypeId) {
    let ability      = abilitiesById[abilityId];
    let tileTypeName = tileTypeById[tileTypeId].name;

    return !!ability.cost[tileTypeName];
}

function abilityCost(abilityId, tileTypeId, tileClassId, warheadWeaponsTotalCost) {
    let ability      = abilitiesById[abilityId];
    let tileTypeName = tileTypeById[tileTypeId].name;

    if(ability.warhead_multiplier){
        return ability.warhead_multiplier * warheadWeaponsTotalCost;
    }

    if (ability.cost.static) {
        return ability.cost.static;
    }
    let isVehicle = tileTypeId === TILE_TYPE_VEHICLE_ID;
    if (isVehicle) {
        return ability.cost[tileTypeName][tileClassId];
    }
    return ability.cost[tileTypeName];
}

function abilityIsDefensive(abilityId) {
    return abilitiesById[abilityId].is_defensive;
}

function hasDefensiveAbility(abilityIds) {
    return find(abilityIds, (abilityId) => {
        return abilityIsDefensive(abilityId);
    });
}

export {
    abilitiesById,
    abilityValid,
    abilityCost,
    abilityIsDefensive,
    hasDefensiveAbility,
};

import abilityData from '../../../source-data/imported/abilities.json';

import {keyBy, find} from 'lodash';
import {TILE_TYPE_VEHICLE_ID} from './constants';
import {tileTypeById} from './options';

const abilitiesById = keyBy(abilityData, 'id');

function abilityValid(abilityId, tileTypeId) {
    let ability      = abilitiesById[abilityId];
    let tileTypeName = tileTypeById[tileTypeId].name;

    return !!ability[tileTypeName + '_valid'];
}

function abilityCost(abilityId, tileTypeId, tileClassId, warheadWeaponsTotalCost) {
    let ability = abilitiesById[abilityId];
    let tileTypeName = tileTypeById[tileTypeId].name;
    if (!abilityValid(abilityId, tileTypeId)) {
        throw new Error('invalid ability: ' + ability.display_name + ', for tile type: ' + tileTypeName);
    }
    if (ability.warhead_cost_multiplier) {
        return ability.warhead_cost_multiplier * warheadWeaponsTotalCost;
    }

    if (ability.cost_static) {
        return ability.cost_static;
    }

    let isVehicle = tileTypeId === TILE_TYPE_VEHICLE_ID;
    if (isVehicle) {
        return ability['cost_vehicle_class_' + tileClassId];
    }
    return ability['cost_' + tileTypeName] || 0;
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

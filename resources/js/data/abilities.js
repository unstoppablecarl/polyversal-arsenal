import abilityDataRaw from '../../../source-data/imported/abilities.json';

import {keyBy, find} from 'lodash';
import {TILE_TYPE_VEHICLE_ID} from './constants';
import {tileTypeById} from './options';

let abilityTooltips = {
    jump_jets: {
        tooltip_title: 'Jump System',
        tooltip_content: 'When selected, combatant tile will have "Jump systems offline" result added to the damage track',
    },
    fallout_shelter: {
        tooltip_title: 'Armor 2 Requirement',
        tooltip_content: 'Only buildings with armor 2 or greater may be Fallout Shelters.',
    },
    ews_communications_center: {
        tooltip_title: 'Class 3 Requirement',
        tooltip_content: 'Only buildings with class 3 or greater may be EWS Communication Centers.',
    }
}

abilityDataRaw.forEach((item) => {
    let tooltip = abilityTooltips[item.name]

    if (tooltip) {
        Object.assign(item, tooltip)
    }
})

export const abilityData = abilityDataRaw

const abilitiesById = keyBy(abilityData, 'id');


function abilityValid(abilityId, tileTypeId, tile) {
    return abilityTileTypeValid(abilityId, tileTypeId, tile) && abilityRequirementsValid(abilityId, tile)
}

function abilityTileTypeValid(abilityId, tileTypeId) {
    let ability = abilitiesById[abilityId];
    let tileTypeName = tileTypeById[tileTypeId].name;
    return !!ability[tileTypeName + '_valid'];
}

function abilityRequirementsValid(abilityId, tile) {
    let ability = abilitiesById[abilityId];

    if (ability.name === 'fallout_shelter') {
        return tile.armor >= 2;
    }
    if (ability.name === 'ews_communications_center') {
        return tile.tile_class_id >= 3;
    }

    return true
}

function abilityCost(abilityId, tileTypeId, tileClassId, tileWeapons) {
    let ability = abilitiesById[abilityId];
    let tileTypeName = tileTypeById[tileTypeId].name;
    if (!abilityTileTypeValid(abilityId, tileTypeId)) {
        throw new Error('invalid ability: ' + ability.display_name + ', for tile type: ' + tileTypeName);
    }
    if (ability.warhead_cost_multiplier) {
        let warheadCost = 0;
        tileWeapons
            .filter((tileWeapon) => tileWeapon.weapon.has_warheads)
            .forEach((tileWeapon) => {
                warheadCost += tileWeapon.quantity * tileWeapon.weapon.class * ability.warhead_cost_multiplier
            })
        return warheadCost;
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
    abilityTileTypeValid,
    abilityRequirementsValid,
    abilityCost,
    abilityIsDefensive,
    hasDefensiveAbility,
};

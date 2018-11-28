import {TILE_TYPE_CAVALRY_ID, TILE_TYPE_INFANTRY_ID, TILE_TYPE_VEHICLE_ID} from './constants';
import {armorOptionsByTileTypeId} from './options-armor';

function makeOptions(maxStealth) {
    let options = [{
        id: 0,
        cost: 0,
        display_name: 'None',
    }];

    for (let i = 1; i <= maxStealth; i++) {
        options.push({
            id: i,
            cost: i,
            display_name: 'Stealth ' + i,
        });
    }

    return options;
}

function getStealthOptions(tileTypeId, tileClassId) {
    if (tileTypeId == TILE_TYPE_VEHICLE_ID) {
        return makeOptions(tileClassId);
    }
    if (tileTypeId == TILE_TYPE_INFANTRY_ID) {
        return makeOptions(2);
    }

    if (tileTypeId == TILE_TYPE_CAVALRY_ID) {
        return makeOptions(3);
    }
}

function getMaxStealth(tileTypeId, tileClassId) {
    if (tileTypeId == TILE_TYPE_VEHICLE_ID) {
        return tileClassId;
    }
    if (tileTypeId == TILE_TYPE_INFANTRY_ID) {
        return 2;
    }

    if (tileTypeId == TILE_TYPE_CAVALRY_ID) {
        return 3;
    }
}

export {
    getStealthOptions,
    getMaxStealth,
};


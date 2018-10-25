import infantry from '../../../source-data/infantry-chassis.json';
import cavalry from '../../../source-data/cavalry-chassis.json';
import vehicle from '../../../source-data/vehicle-chassis.json';
import {TILE_TYPE_CAVALRY_ID, TILE_TYPE_INFANTRY_ID, TILE_TYPE_VEHICLE_ID} from './constants';
import {mobilityIdToName} from './mobility';
import {techLevelIdToName} from './tech-level';

let map = {
    [TILE_TYPE_INFANTRY_ID]: infantry,
    [TILE_TYPE_CAVALRY_ID]: cavalry,
    [TILE_TYPE_VEHICLE_ID]: vehicle,
};

function getChassis(tileTypeId, tileClassId, armor, techLevelId, mobilityId){

    let data = map[tileTypeId];
    let mobility = mobilityIdToName[mobilityId];
    let techLevel = techLevelIdToName[techLevelId];

    if(tileTypeId == TILE_TYPE_INFANTRY_ID){
        return data[mobility][techLevel][armor];
    }
    else if(tileTypeId == TILE_TYPE_CAVALRY_ID){
        return data[mobility][techLevel][armor];
    }
    else if(tileTypeId == TILE_TYPE_VEHICLE_ID){
        return data[tileClassId][mobility][techLevel][armor];
    } else {
        throw new Error('invalid tileTypeId : ' + tileTypeId);
    }
}

export {
    getChassis
}

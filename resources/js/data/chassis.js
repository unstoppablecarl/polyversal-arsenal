import infantry from '../../../source-data/imported/infantry-chassis.json';
import cavalry from '../../../source-data/imported/cavalry-chassis.json';
import vehicle from '../../../source-data/imported/vehicle-chassis.json';
import {TILE_TYPE_CAVALRY_ID, TILE_TYPE_INFANTRY_ID, TILE_TYPE_VEHICLE_ID} from './constants';
import {mobilityById} from './options-mobility';
import {techLevelById} from './options';

let map = {
    [TILE_TYPE_INFANTRY_ID]: infantry,
    [TILE_TYPE_CAVALRY_ID]: cavalry,
    [TILE_TYPE_VEHICLE_ID]: vehicle,
};

function getChassis({
                        type_id,
                        class_id,
                        armor,
                        tech_level_id,
                        mobility_id,
                    }) {

    let data      = map[type_id];
    let mobility  = mobilityById[mobility_id].name;
    let techLevel = techLevelById[tech_level_id].name;
    let isVehicle = type_id == TILE_TYPE_VEHICLE_ID;

    if (isVehicle) {
        return data[class_id][mobility][techLevel][armor];
    } else {
        return data[mobility][techLevel][armor];
    }
}

export {
    getChassis,
};

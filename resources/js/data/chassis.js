import infantry from '../../../source-data/imported/infantry-chassis.json'
import cavalry from '../../../source-data/imported/cavalry-chassis.json'
import vehicle from '../../../source-data/imported/vehicle-chassis.json'
import building from '../../../source-data/imported/building-chassis.json'

import {TILE_TYPE_BUILDING_ID, TILE_TYPE_CAVALRY_ID, TILE_TYPE_INFANTRY_ID, TILE_TYPE_VEHICLE_ID} from './constants'
import {mobilityById} from './options-mobility'
import {techLevelById} from './options'

let map = {
    [TILE_TYPE_INFANTRY_ID]: infantry,
    [TILE_TYPE_CAVALRY_ID]: cavalry,
    [TILE_TYPE_VEHICLE_ID]: vehicle,
    [TILE_TYPE_BUILDING_ID]: building,
}

function getChassis({
                        tile_type_id,
                        tile_class_id,
                        armor,
                        tech_level_id,
                        mobility_id,
                    }) {
    //
    // console.log('getChassis 1', {
    //     tile_type_id,
    //     tile_class_id,
    //     armor,
    //     tech_level_id,
    //     mobility_id,
    // })
    let data = map[tile_type_id]
    let mobility = mobilityById[mobility_id].name
    let techLevel = techLevelById[tech_level_id].name

    // console.log('z', data[tile_type_id][mobility][techLevel], armor)
// console.log('getChassis 2', data[tile_class_id][mobility][techLevel])
    return data[tile_class_id][mobility][techLevel][armor]
}

export {
    getChassis,
}

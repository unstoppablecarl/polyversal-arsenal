import {TILE_TYPE_CAVALRY_ID, TILE_TYPE_INFANTRY_ID, TILE_TYPE_VEHICLE_ID, TILE_TYPE_BUILDING_ID} from './constants'

const armorOptionsByTileTypeId = {
    [TILE_TYPE_INFANTRY_ID]: makeArmorOptions([0, 1, 2, 3]),
    [TILE_TYPE_CAVALRY_ID]: makeArmorOptions([0, 1, 2, 3]),
    [TILE_TYPE_VEHICLE_ID]: makeArmorOptions([0, 1, 2, 3, 4, 5]),
}

const armorOptionsByBuildingClass = {
    1: makeBuildingArmorOptions([0, 1]),
    2: makeBuildingArmorOptions([0, 1, 2]),
    3: makeBuildingArmorOptions([1, 2, 3]),
    4: makeBuildingArmorOptions([2, 3, 4]),
    5: makeBuildingArmorOptions([3, 4, 5]),
}

function getArmorOptions(tile_type_id, tile_class_id) {

    if (tile_type_id === TILE_TYPE_BUILDING_ID) {
        return armorOptionsByBuildingClass[tile_class_id]
    }

    return armorOptionsByTileTypeId[tile_type_id]
}

function makeArmorOptions(values) {
    return values.map((id) => {
        return {
            id,
            cost: id,
            display_name: 'Armor ' + id,
        }
    })
}

function makeBuildingArmorOptions(values) {
    return values.map((id) => {
        return {
            id,
            cost: 0,
            display_name: 'Armor ' + id,
        }
    })
}

function getMaxArmor(tile_type_id, tile_class_id) {
    let options = getArmorOptions(tile_type_id, tile_class_id)

    let max = 0

    options.forEach(({id}) => {
        if (id > max) {
            max = id
        }
    })

    return max
}

function getMinArmor(tile_type_id, tile_class_id) {
    let options = getArmorOptions(tile_type_id, tile_class_id)

    let min = Infinity

    options.forEach(({id}) => {
        if (id < min) {
            min = id
        }
    })

    return min
}

function normalizeArmor(tile_type_id, tile_class_id, armor) {

    let min = getMinArmor(tile_type_id, tile_class_id)
    let max = getMaxArmor(tile_type_id, tile_class_id)

    if (armor < min) {
        return min
    }

    if (armor > max) {
        return max
    }
    return armor
}

export {
    normalizeArmor,
    getArmorOptions,
}

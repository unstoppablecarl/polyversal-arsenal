import {TILE_TYPE_CAVALRY_ID, TILE_TYPE_INFANTRY_ID, TILE_TYPE_VEHICLE_ID} from './constants';

const armorOptionsByTileTypeId = {
    [TILE_TYPE_INFANTRY_ID]: make([0, 1, 2, 3]),
    [TILE_TYPE_CAVALRY_ID]: make([0, 1, 2, 3]),
    [TILE_TYPE_VEHICLE_ID]: make([0, 1, 2, 3, 4, 5]),
};

function make(values) {
    return values.map((id) => {
        return {
            id,
            cost: id,
            display_name: 'Armor ' + id,
        };
    });
}

function maxArmorForTileType(typeId) {
    let armorOptions = armorOptionsByTileTypeId[typeId];
    let keys         = Object.keys(armorOptions);
    let last         = keys[keys.length - 1];
    return parseInt(last, 10);
}

export {
    armorOptionsByTileTypeId,
    maxArmorForTileType

}

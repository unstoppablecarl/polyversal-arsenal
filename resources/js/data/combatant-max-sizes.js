import {TILE_TYPE_CAVALRY_ID, TILE_TYPE_INFANTRY_ID} from './constants';

const vehicleClassMax = {
    1: 6,
    2: 5,
    3: 4,
    4: 3,
    5: 1,
};

export default function getMaxSize(tileTypeId, tileClassId) {
    if (tileTypeId == TILE_TYPE_INFANTRY_ID) {
        return 10;
    }

    if (tileTypeId == TILE_TYPE_CAVALRY_ID) {
        return 10;
    }

    return vehicleClassMax[tileClassId];
}

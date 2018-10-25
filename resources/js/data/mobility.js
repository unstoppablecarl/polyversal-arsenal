import mobilityData from '../../../source-data/mobility.json';

import {
    TILE_TYPE_CAVALRY_ID,
    TILE_TYPE_INFANTRY_ID,
    TILE_TYPE_VEHICLE_ID,
} from './constants';
import {toIdDisplayName, toIdValue} from './helpers';

let mobility = {
    [TILE_TYPE_INFANTRY_ID]: mobilityData.infantry,
    [TILE_TYPE_CAVALRY_ID]: mobilityData.cavalry,
    [TILE_TYPE_VEHICLE_ID]: mobilityData.vehicle,
};

const mobilityOptions = {
    [TILE_TYPE_INFANTRY_ID]: toIdDisplayName(mobilityData.infantry),
    [TILE_TYPE_CAVALRY_ID]: toIdDisplayName(mobilityData.cavalry),
    [TILE_TYPE_VEHICLE_ID]: toIdDisplayName(mobilityData.vehicle),
};

const all                     = [].concat(mobilityData.infantry, mobilityData.cavalry, mobilityData.vehicle);
const mobilityIdToName        = toIdValue(all, 'name');
const mobilityIdToDisplayName = toIdDisplayName(all);

export {
    mobility,
    mobilityOptions,
    mobilityIdToName,
    mobilityIdToDisplayName,
};


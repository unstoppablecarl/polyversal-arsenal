import mobilityData from '../../../source-data/static/mobility.json';
import {groupBy, keyBy} from 'lodash';

const mobilityOptionsByTileTypeId = groupBy(mobilityData, 'tile_type_id');
const mobilityById                = keyBy(mobilityData, 'id');


function firstMobilityIdForTileType(typeId) {
    let options  = mobilityOptionsByTileTypeId[typeId];
    let firstKey = Object.keys(options)[0];
    return options[firstKey].id;
}

export {
    mobilityOptionsByTileTypeId,
    mobilityById,
    firstMobilityIdForTileType
};


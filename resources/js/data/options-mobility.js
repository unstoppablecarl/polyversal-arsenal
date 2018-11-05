import mobilityData from '../../../source-data/static/mobility.json';
import {groupBy, keyBy} from 'lodash';

const mobilityOptionsByTileTypeId = groupBy(mobilityData, 'tile_type_id');
const mobilityById                = keyBy(mobilityData, 'id');

export {
    mobilityOptionsByTileTypeId,
    mobilityById,
};


import techLevelData from '../../../source-data/tech-level.json';

import {toIdDisplayName, toIdValue} from './helpers';

const techLevelOptions = toIdDisplayName(techLevelData);
const techLevelIdToName = toIdValue(techLevelData, 'name');

export {
    techLevelOptions,
    techLevelIdToName,
};

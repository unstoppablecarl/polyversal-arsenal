import vehicleClassOptions from '../../../source-data/static/tile-classes.json';
import techLevelOptions from '../../../source-data/static/tech-levels.json';
import attackStats from '../../../source-data/static/combat-values.json';
import tileTypeOptions from '../../../source-data/static/tile-types.json';
import abilityOptionsData from '../../../source-data/static/abilities.json';
import amaOptions from '../../../source-data/static/anti-missile-systems.json';
import arcSizeOptions from '../../../source-data/static/arc-sizes.json';
import arcDirectionOptions from '../../../source-data/static/arc-directions.json';

import {keyBy, sortBy} from 'lodash';

const abilityOptions   = sortBy(abilityOptionsData, 'display_order');
const targetingOptions = attackStats.map(item => Object.assign({}, item));
const assaultOptions   = attackStats.map(item => Object.assign({}, item));

const amaById          = keyBy(amaOptions, 'id');
const targetingById    = keyBy(targetingOptions, 'id');
const vehicleClassById = keyBy(vehicleClassOptions, 'id');
const tileTypeById     = keyBy(tileTypeOptions, 'id');
const techLevelById    = keyBy(techLevelOptions, 'id');
const arcSizeById      = keyBy(arcSizeOptions, 'id');
const arcDirectionById = keyBy(arcDirectionOptions, 'id');

export {
    targetingOptions,
    assaultOptions,
    techLevelOptions,
    vehicleClassOptions,
    tileTypeOptions,
    abilityOptions,
    amaOptions,
    arcSizeOptions,
    arcDirectionOptions,

    vehicleClassById,
    tileTypeById,
    techLevelById,
    targetingById,
    arcSizeById,
    arcDirectionById,


    amaById,
};


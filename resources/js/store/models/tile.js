import {AMA_NONE_ID} from '../../data/constants';
import {makeModel, castInt} from './model';

const defaults = {
    id: null,
    name: null,
    tile_type_id: null,
    tile_class_id: null,
    armor: null,
    tech_level_id: null,
    mobility_id: null,
    targeting_id: 1,
    assault_id: 1,
    stealth: 0,
    anti_missile_system_id: AMA_NONE_ID,
};

const formatters = {
    id: castInt,
    tile_type_id: castInt,
    tile_class_id: castInt,
    armor: castInt,
    tech_level_id: castInt,
    mobility_id: castInt,
    targeting_id: castInt,
    assault_id: castInt,
    stealth: castInt,
    anti_missile_system_id: castInt,
};

const {
          make,
          sanitize,
          formatValues,
          filterKeys,
      } = makeModel({defaults, formatters});

export {
    make,
    sanitize,
    formatValues,
    filterKeys,
};

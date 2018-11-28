import {TILE_WEAPON_TYPE_GROUND_ID} from '../../data/constants';
import {makeModel, castInt} from './model';
const defaults = {
    id: null,
    weapon_id: null,
    tile_weapon_type_id: TILE_WEAPON_TYPE_GROUND_ID,
    quantity: 1,
    arc_direction_id: 1,
    arc_size_id: 1,
    display_order: null,
};

const formatters = {
    weapon_id: castInt,
    tile_weapon_type_id: castInt,
    quantity: castInt,
    arc_direction_id: castInt,
    arc_size_id: castInt,
    display_order: castInt,
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

import model from './model';
import {TILE_WEAPON_TYPE_GROUND_ID} from '../../data/constants';

const factory = model({
    id: null,
    slug: null,
    tile_weapon_type_id: TILE_WEAPON_TYPE_GROUND_ID,
    quantity: 1,
    arc: 'UP_90',
    display_order: null,
});

export default function (data) {
    let instance = factory(data);

    instance.id = parseInt(instance.id, 10);
    instance.quantity = parseInt(instance.quantity, 10);
    console.log(Object.assign({}, instance), Object.assign({},data));
    return instance;
};

import model from './model';
import {TILE_WEAPON_TYPE_GROUND_ID} from '../../data/constants';

const factory = model({
    id: null,
    weapon_id: null,
    tile_weapon_type_id: TILE_WEAPON_TYPE_GROUND_ID,
    quantity: 1,
    arc_direction_id: 1,
    arc_size_id: 1,
    display_order: null,
});

function tileWeaponCreate(data) {

    let instance = factory(data);

    instance.weapon_id        = parseInt(instance.weapon_id, 10);
    instance.quantity         = parseInt(instance.quantity, 10);
    instance.arc_direction_id = parseInt(instance.arc_direction_id, 10);
    instance.arc_size_id      = parseInt(instance.arc_size_id, 10);
    return instance;
}

function tileWeaponUpdate(data) {

    let keys = [
        'weapon_id',
        'quantity',
        'arc_direction_id',
        'arc_size_id',
    ];

    keys.forEach((key) => {
        if (data[key] !== undefined) {
            data[key] = parseInt(data[key], 10);
        }
    });

    return data;
}

export {
    tileWeaponCreate,
    tileWeaponUpdate,
};

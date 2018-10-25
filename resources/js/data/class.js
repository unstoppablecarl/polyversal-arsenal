import {TILE_TYPE_CAVALRY_ID, TILE_TYPE_INFANTRY_ID, TILE_TYPE_VEHICLE_ID} from './constants';
import {toIdDisplayName} from './helpers';


const classes = [1, 2, 3, 4, 5].map((i) => {
    return {
        id: i,
        display_name: 'Class ' + i,
    };
});

const vehicleClasses = [
    {
        id: 1,
        display_name: 'Light',
    },
    {
        id: 2,
        display_name: 'Main',
    },
    {
        id: 3,
        display_name: 'Heavy',
    },
    {
        id: 4,
        display_name: 'Superheavy',
    },
    {
        id: 5,
        display_name: 'Colossal',
    },
];

let map = {
    [TILE_TYPE_INFANTRY_ID]: classes,
    [TILE_TYPE_CAVALRY_ID]: classes,
    [TILE_TYPE_VEHICLE_ID]: vehicleClasses,
};

const classOptions = {
    [TILE_TYPE_INFANTRY_ID]: toIdDisplayName(classes),
    [TILE_TYPE_CAVALRY_ID]: toIdDisplayName(classes),
    [TILE_TYPE_VEHICLE_ID]: toIdDisplayName(vehicleClasses),
};

export {
    classOptions,
}

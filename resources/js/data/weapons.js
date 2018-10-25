import {
    NONE,
    TILE_WEAPON_TYPE_GROUND_ID,
    TILE_WEAPON_TYPE_ONLY_AA_ID,
    TILE_WEAPON_TYPE_WITH_AA_ID,
    TILE_WEAPON_TYPE_WITH_AA,
    TILE_WEAPON_TYPE_ONLY_AA,
    TILE_WEAPON_TYPE_GROUND,
    TILE_TYPE_INFANTRY_ID,
    TILE_TYPE_CAVALRY_ID, TILE_TYPE_VEHICLE_ID,
} from './constants';

import infantryData from './weapons-infantry.json';
import vehicleData from './weapons-vehicle.json';

const infantry = Repo(infantryData);
const vehicle  = Repo(vehicleData);

const map = {
    [TILE_TYPE_INFANTRY_ID]: infantry,
    [TILE_TYPE_CAVALRY_ID]: infantry,
    [TILE_TYPE_VEHICLE_ID]: vehicle,
};

export default function (tileTypeId) {
    return map[tileTypeId];
}

function Repo(data) {
    let defaults = {
        aa: NONE,
        at: NONE,
        ap: NONE,
        is_indirect: false,
        is_ama: false,
    };

    const repo = {};

    Object.keys(data).forEach((key) => {
        let item  = data[key];
        repo[key] = Object.assign({}, defaults, item);
    });

    function all() {
        return repo;
    }

    function toItem(slug) {
        let item = repo[slug];
        if (!item) {
            throw new Error('invalid weapon slug: ' + slug);
        }
        return item;
    }

    function firstKey() {
        let keys = Object.keys(repo);
        return keys[0];
    }

    function get(slug, tileWeaponTypeId = 1) {
        if (tileWeaponTypeId === TILE_WEAPON_TYPE_GROUND_ID) {
            return getGround(slug);
        }

        if (tileWeaponTypeId === TILE_WEAPON_TYPE_WITH_AA_ID) {
            return getWithAA(slug);
        }

        if (tileWeaponTypeId === TILE_WEAPON_TYPE_ONLY_AA_ID) {
            return getOnlyAA(slug);
        }
        throw new Error('invalid tile weapon type');
    }

    function getGround(slug) {
        if (!hasGround(slug)) {
            return false;
        }
        let item = toItem(slug);
        return Object.assign({}, item, {
            aa: NONE,
        });
    }

    function getWithAA(slug) {
        if (!hasWithAA(slug)) {
            return false;
        }
        let item = toItem(slug);
        return Object.assign({}, item);
    }

    function getOnlyAA(slug) {

        if (!hasOnlyAA(slug)) {
            return false;
        }

        let item = toItem(slug);

        return Object.assign({}, item, {
            at: NONE,
            ap: NONE,
        });
    }

    function hasGround(slug) {
        return validTileWeaponType(slug, TILE_WEAPON_TYPE_GROUND);
    }

    function hasWithAA(slug) {
        return validTileWeaponType(slug, TILE_WEAPON_TYPE_WITH_AA);
    }

    function hasOnlyAA(slug) {
        return validTileWeaponType(slug, TILE_WEAPON_TYPE_ONLY_AA);
    }

    function validTileWeaponType(slug, tileWeaponTypeName) {
        if (slug === null) {
            return false;
        }
        let item = toItem(slug);
        if (!item.valid_tile_weapon_type_ids) {
            return true;
        }
        return item.valid_tile_weapon_type_ids.indexOf(tileWeaponTypeName) !== -1;
    }

    function tileWeaponTypeSelect(slug) {

        let types = {};

        if (hasGround(slug)) {
            types[TILE_WEAPON_TYPE_GROUND_ID] = 'Ground';
        }
        if (hasWithAA(slug)) {
            types[TILE_WEAPON_TYPE_WITH_AA_ID] = 'With AA';
        }
        if (hasOnlyAA(slug)) {
            types[TILE_WEAPON_TYPE_ONLY_AA_ID] = 'Only AA';
        }
        return types;
    }

    return {
        all,
        firstKey,
        get,
        hasGround,
        hasOnlyAA,
        hasWithAA,
        tileWeaponTypeSelect,
    };
}

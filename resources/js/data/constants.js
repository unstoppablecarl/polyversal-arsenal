const DMG_L    = 'L';
const DMG_ML   = 'ML';
const DMG_M    = 'M';
const DMG_MH   = 'MH';
const DMG_H    = 'H';
const DMG_NONE = 'NONE';

const D4   = 'D4';
const D6   = 'D6';
const D8   = 'D8';
const D10  = 'D10';
const D12  = 'D12';
const NONE = 'NONE';

const DIE_COLORS = {
    [D4]: '#1B75BC',
    [D6]: '#009444',
    [D8]: '#FFDE17',
    [D10]: '#F26522',
    [D12]: '#ED1C24',
};

const ARC_90  = 'ARC_90';
const ARC_180 = 'ARC_180';
const ARC_270 = 'ARC_270';
const ARC_360 = 'ARC_360';

const ARC_UP    = 'ARC_UP';
const ARC_LEFT  = 'ARC_LEFT';
const ARC_RIGHT = 'ARC_RIGHT';
const ARC_DOWN  = 'ARC_DOWN';

const ATK_VALUES = {
    NONE,
    D4,
    D6,
    D8,
    D10,
    D12,
};

const DMG_VALUES = {
    DMG_NONE,
    DMG_L,
    DMG_ML,
    DMG_M,
    DMG_MH,
    DMG_H,
};

const ARC_SIZES = {
    ARC_90,
    ARC_180,
    ARC_270,
    ARC_360,
};

const ARC_DIRECTIONS = {
    ARC_UP,
    ARC_LEFT,
    ARC_RIGHT,
    ARC_DOWN,
};

const TILE_WEAPON_TYPE_GROUND_ID  = 1;
const TILE_WEAPON_TYPE_WITH_AA_ID = 2;
const TILE_WEAPON_TYPE_ONLY_AA_ID = 3;

const TILE_WEAPON_TYPE_GROUND  = 'TILE_WEAPON_TYPE_GROUND';
const TILE_WEAPON_TYPE_WITH_AA = 'TILE_WEAPON_TYPE_WITH_AA';
const TILE_WEAPON_TYPE_ONLY_AA = 'TILE_WEAPON_TYPE_ONLY_AA';

const TILE_TYPE_INFANTRY_ID = 1;
const TILE_TYPE_CAVALRY_ID  = 2;
const TILE_TYPE_VEHICLE_ID  = 3;

export {

    TILE_TYPE_INFANTRY_ID,
    TILE_TYPE_CAVALRY_ID,
    TILE_TYPE_VEHICLE_ID,

    TILE_WEAPON_TYPE_GROUND_ID,
    TILE_WEAPON_TYPE_WITH_AA_ID,
    TILE_WEAPON_TYPE_ONLY_AA_ID,

    TILE_WEAPON_TYPE_GROUND,
    TILE_WEAPON_TYPE_WITH_AA,
    TILE_WEAPON_TYPE_ONLY_AA,

    DIE_COLORS,

    ATK_VALUES,
    D4,
    D6,
    D8,
    D10,
    D12,
    NONE,

    DMG_VALUES,
    DMG_L,
    DMG_ML,
    DMG_M,
    DMG_MH,
    DMG_H,
    DMG_NONE,

    ARC_SIZES,
    ARC_90,
    ARC_180,
    ARC_270,
    ARC_360,

    ARC_DIRECTIONS,
    ARC_UP,
    ARC_LEFT,
    ARC_RIGHT,
    ARC_DOWN,

};

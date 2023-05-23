import tile from './tile';
import abilities from './abilities';
import tile_weapons from './tile-weapons';
import tile_print_settings from './tile-print-settings';

import server from './server-repo';
import images from './images';

import {notificationFromErrorResponse, notificationSuccess, notificationWarning} from './notification';
import {router} from '../app';

export default {
    state() {
        return {
            fetching: false,
            saving: false,
        }
    },
    actions: {
        set({commit, dispatch}, data) {
            dispatch('tile/update', data.tile);
            dispatch('abilities/set', data.ability_ids);
            dispatch('tile_weapons/set', data.tile_weapons);
            dispatch('images/front/setSourceImageUrl', data.tile.front_source_image_url);
            dispatch('images/back/setSourceImageUrl', data.tile.back_source_image_url);
            dispatch('setTilePrintSettings', data.tile_print_settings || {});

            return data
        },

        fetch({commit, state, dispatch}, tileId) {
            state.fetching = true;
            return server.fetch(tileId)
                .then((response) => {
                    notificationSuccess({
                        title: 'Tile Loaded',
                    });
                    let data = response.data.data;

                    return dispatch('set', data);
                })
                .catch((error) => {
                    console.error(error);
                    if (error.response.status == 404) {
                        return {
                            not_found: true,
                        };
                    }

                    console.log(error.response.status);
                    if (error.response.status == 401) {
                        return {
                            unauthorized: true,
                        };
                    }
                    notificationFromErrorResponse(error.response);
                })
                .finally(() => {
                    state.fetching = false;
                });
        },
        save({commit, state, getters, dispatch}) {
            state.saving = true;
            let data = _.pick(state.tile, [
                'id',
                'name',
                'tile_type_id',
                'tile_class_id',
                'armor',
                'tech_level_id',
                'mobility_id',
                'targeting_id',
                'assault_id',
                'stealth',
                'anti_missile_system_id',
                'flavor_text',
            ]);

            data.tile_weapons = state.tile_weapons.tile_weapons;
            data.ability_ids = state.abilities.ability_ids;

            data.costs = {
                total: getters.totalCost,
                stats: getters['tile/statsTotalCost'],
                tile_weapons: getters['tile_weapons/totalCost'],
                abilities: getters['abilities/totalCost'],
            };

            data.tile_print_settings = _.pick(state.tile_print_settings, [
                'front_is_printer_friendly',
                'front_invert_title',
                'front_invert_abilities',
                'front_cut_line_color',

                'back_is_printer_friendly',
                'back_invert_title',
                'back_invert_bottom_headings',
                'back_invert_flavor_text',
                'back_cut_line_color',
            ]);

            const handleError = (error) => {
                console.error(error);
                notificationFromErrorResponse(error.response);
            };

            const handelResponse = (response) => {

                let payload = response.data.data;

                if (payload.cost_diff) {
                    console.warn('server disagrees with cost calculation!', payload.cost_diff);

                    notificationWarning({
                        title: 'Cost Calc Error',
                        text: `Server disagrees with cost calculation. ` + costDiffToMessages(payload),
                    });
                }

                dispatch('set', payload);

                if (router.currentRoute.params.id == 'create') {
                    router.push({name: router.currentRoute.name, params: {id: payload.tile.id}});

                }
            };

            return Promise.all([
                dispatch('images/front/getImageBase64'),
                dispatch('images/front/getSvgString'),
                dispatch('images/back/getImageBase64'),
                dispatch('images/back/getSvgString'),
            ])
                .then((results) => {
                    data.new_front_image = results[0];
                    data.new_front_svg = results[1];
                    data.new_back_image = results[2];
                    data.new_back_svg = results[3];
                    return sendRequest();
                })
                .finally(() => {
                    state.saving = false;
                    state.images.front.unsavedChanges = false;
                    state.images.back.unsavedChanges = false;
                });

            function sendRequest() {

                if (!state.tile.id) {
                    return server.create(data)
                        .then((response) => {
                            handelResponse(response);
                            notificationSuccess({
                                title: 'Tile Saved',
                            });
                        })
                        .catch(handleError);
                } else {
                    return server.update(data)
                        .then((response) => {
                            handelResponse(response);
                            notificationSuccess({
                                title: 'Tile Saved',
                            });
                        })
                        .catch(handleError);
                }
            }
        },
        saveSourceImageFront({state, dispatch}, newImageData) {
            return saveSourceImage({state, dispatch}, 'front', newImageData);
        },
        saveSourceImageBack({state, dispatch}, newImageData) {
            return saveSourceImage({state, dispatch}, 'back', newImageData);
        },
    },
    getters: {
        totalCost(state, getters) {
            return getters['tile/statsTotalCost'] + getters['abilities/totalCost'] + getters['tile_weapons/totalCost'];
        },
        viewURL(state) {
            return server.viewURL(state.tile.id);
        },
        editURL(state) {
            return server.editURL(state.tile.id);
        },
        deleteURL(state) {
            return server.deleteURL(state.tile.id);
        },
        syncing(state, getters) {
            return state.saving || getters['images/front/syncing'] || getters['images/back/syncing'];
        },
        saving(state) {
            return state.saving;
        },
        fetching(state) {
            return state.fetching;
        },
    },
    modules: {
        tile,
        abilities,
        tile_weapons,
        images,
        tile_print_settings,
    },
};

function saveSourceImage({state, dispatch}, side, newImageData) {

    let action = 'images/' + side + '/saveSourceImage';
    let saveSourceImage = () => dispatch(action, newImageData);

    if (!state.tile.id) {
        return dispatch('save')
            .then(() => saveSourceImage());
    }
    return saveSourceImage();
}

function costDiffToMessages(payload) {
    let messages = [];
    payload.cost_diff.forEach(({
                                   app,
                                   key,
                                   vue,
                               }) => {

        messages.push(`${key} cost: server=${app}, local=${vue} `);
    });
    return messages.join(', ');
}

import Vuex from 'vuex';
import tile from './tile';
import abilities from './abilities';
import tile_weapons from './tile-weapons';
import server from './server-repo';
import {notificationFromErrorResponse, notificationSuccess} from './notification';

export default new Vuex.Store({
    mutations: {},
    actions: {
        set({commit, dispatch}, data) {
            dispatch('tile/update', data.tile);
            dispatch('abilities/set', data.ability_ids);
            dispatch('tile_weapons/set', data.tile_weapons);
        },
        update({commit, dispatch}, data) {
            dispatch('tile/update', data.tile);
            dispatch('abilities/set', data.ability_ids);
            dispatch('tile_weapons/updateAll', data.tile_weapons);
        },
        fetch({commit, state, dispatch}, tileId) {
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
                });
        },
        save({commit, state, getters, dispatch}) {
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
            ]);

            data.tile_weapons = state.tile_weapons.tile_weapons;
            data.ability_ids  = state.abilities.ability_ids;

            data.costs = {
                total: getters.totalCost,
                stats: getters['tile/statsTotalCost'],
                tile_weapons: getters['tile_weapons/totalCost'],
                abilities: getters['abilities/totalCost'],
            };

            const handleError = (error) => {
                console.error(error);
                notificationFromErrorResponse(error.response);
            };

            const handelSuccess = (payload) => {


                if (payload.cost_diff) {
                    console.warn('server disagrees with cost calculation!', payload.cost_diff);
                }
            };

            if (!state.tile.id) {
                return server.create(data)
                    .then((response) => {
                        notificationSuccess({
                            title: 'Tile Created',
                        });
                        let payload = response.data.data;
                        handelSuccess(payload);
                        return dispatch('set', payload);
                    })
                    .catch(handleError);
            }
            else {
                return server.update(data)
                    .then((response) => {
                        notificationSuccess({
                            title: 'Tile Saved',
                        });
                        let payload = response.data.data;
                        handelSuccess(payload);
                        return dispatch('update', payload);
                    })
                    .catch(handleError);
            }
        },
    },
    getters: {
        totalCost(state, getters) {
            return getters['tile/statsTotalCost'] +
                getters['abilities/totalCost'] +
                getters['tile_weapons/totalCost'];
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
    },
    modules: {
        tile,
        abilities,
        tile_weapons,
    },
});

import Vuex from 'vuex';
import {defActions, defGetters} from './helpers/store-mappers';
import _ from 'lodash'
import {tileSlotStores} from './tile-sheet-slot-instances'

const maxTileSlots = 5;

export default new Vuex.Store({
    namespaced: false,

    state() {
        return {
            tileSlots: [],
            tilesById: {},
            generating: false,
            pdfBase64: null,
        }
    },
    mutations: {
        setGenerating(state, value) {
            state.generating = value
        },
        setPdfBase64(state, value) {
            state.pdfBase64 = value
        },
        add(state, {tileId, side = 'front'}) {
            if (state.tileSlots.length >= maxTileSlots) {
                throw new Error('max tile slots set')
            }

            state.tileSlots.push({
                id: tileId,
                side
            })
        },
        setSide(state, {index, side}) {
            let tileSlot  = state.tileSlots[index];
            tileSlot.side = side;
        },
        deleteIndex(state, index) {
            state.tileSlots.splice(index, 1);
        },
        delete(state, {tileId, side}) {

            let index = _.findLastIndex(state.tileSlots, (item) => {
                return item.id == tileId && item.side == side;
            })

            state.tileSlots.splice(index, 1);
        },
        cacheTileData(state, tileData) {
            state.tilesById[tileData.tile.id] = tileData
        }
    },
    actions: {
        ...defActions([
            'setSide',
        ]),
        add({commit, dispatch, state}, {tileId, side = 'front'}) {

            let nextIndex     = state.tileSlots.length;
            let tileSlotStore = tileSlotStores[nextIndex]

            let cached = state.tilesById[tileId];

            if (cached) {
                return tileSlotStore.dispatch('set', cached)
                    .then((tileData) => {
                        commit('add', {tileId, side})
                    })
            }

            return tileSlotStore.dispatch('fetch', tileId)
                .then((tileData) => {
                    commit('cacheTileData', tileData)
                    commit('add', {tileId, side})
                })
        },
        deleteIndex({commit, dispatch}, index) {
            commit('deleteIndex', index)
            return dispatch('resync')
        },
        delete({commit, dispatch}, {tileId, side}) {
            commit('delete', {tileId, side})

            return dispatch('resync')
        },
        resync({state}) {
            state.tileSlots.forEach(({id, side}, index) => {

                let tileSlotStore  = tileSlotStores[index]
                let cachedTileData = state.tilesById[id];

                tileSlotStore.dispatch('set', cachedTileData);

            })

            return Promise.resolve()
        },
    },
    getters: {
        ...defGetters([
            'generating',
        ]),
        getCounts(state) {
            return (tileId) => {

                let front = 0;
                let back  = 0;

                state.tileSlots.forEach(({id, side}) => {

                    if (id !== tileId) {
                        return
                    }

                    if (side === 'front') {
                        front++
                    } else {
                        back++
                    }
                })

                return {front, back}
            }
        },
        frontsAddedToSheet(state) {
            return state.tileSlots
                .filter(({id, side}) => {
                    return side == 'front'
                })
                .map(({id, side}) => {
                    return id
                })
        },
        backsAddedToSheet(state) {
            return state.tileSlots
                .filter(({id, side}) => {
                    return side == 'back'
                })
                .map(({id, side}) => {
                    return id
                })
        },
        getFrontAddedToSheet(state, getters) {
            return (tileId) => {
                return getters.frontsAddedToSheet.indexOf(tileId) !== -1
            }
        },
        getBackAddedToSheet(state, getters) {
            return (tileId) => {
                return getters.backsAddedToSheet.indexOf(tileId) !== -1
            }
        },
        tileSlotsEmpty(state) {
            return state.tileSlots.length === 0
        },
        tileSlotsFull(state) {
            return state.tileSlots.length >= maxTileSlots
        },
        tileSlots(state) {
            return state.tileSlots.map(({id, side}) => {
                return {
                    tile: state.tilesById[id].tile,
                    side,
                }
            })
        },
        images(state) {
            return state.tileSlots.map((tileSlot) => {
                let tile = state.tilesById[tileSlot.id];

                if (tileSlot.side === 'front') {
                    return tile.front_image_url;
                } else {
                    return tile.back_image_url;
                }
            })
        }
    },
})


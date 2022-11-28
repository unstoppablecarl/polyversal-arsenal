import Vuex from 'vuex';
import {defActions, defGetters} from './helpers/store-mappers';
import _ from 'lodash'
import {makeBase64Pdf} from '../lib/tile-sheet-pdf';
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
        add(state, {tile, side = 'front'}) {
            if (state.tileSlots.length >= maxTileSlots) {
                throw new Error('max tile slots set')
            }

            state.tileSlots.push({
                id: tile.id,
                side
            })

            state.tilesById[tile.id] = Object.freeze(tile)


            let lastIndex     = state.tileSlots.length - 1;
            let tileSlotStore = tileSlotStores[lastIndex]

            tileSlotStore.dispatch('fetch', tile.id)
        },
        setSide(state, {index, side}) {
            let tileSlot  = state.tileSlots[index];
            tileSlot.side = side;

        },
        deleteIndex(state, index) {
            let tileId = state.tileSlots[index].id
            state.tileSlots.splice(index, 1);
        },
        delete(state, {tile, side}) {

            let index = _.findLastIndex(state.tileSlots, (item) => {
                return item.id == tile.id && item.side == side;
            })

            state.tileSlots.splice(index, 1);
        },
    },
    actions: {
        ...defActions([
            'add',
            'setSide',
            'delete',
            'deleteIndex'
        ]),
        generate({commit, state, getters}, {manualOffsetX, margin}) {

            commit('setGenerating', true)

            let promises = state.tileSlots.map((tileSlot, index) => {

                let namespace = tileSlot.side;
                let store     = tileSlotStores[index];

                return store.dispatch('images/' + namespace + '/getImageBase64');
            })

            Promise.all(promises)
                .then((base64Images) => {
                    return makeBase64Pdf(base64Images, manualOffsetX, margin)
                        .then((url) => {
                            commit('setPdfBase64', url)
                            commit('setGenerating', false)
                            return url
                        })
                        .catch((err) => {
                            commit('setGenerating', false)
                            throw err
                        })
                })


        }
    },
    getters: {
        ...defGetters([
            'generating',
            'pdfBase64'
        ]),
        getCounts(state) {
            return (tile) => {

                let front = 0;
                let back  = 0;

                state.tileSlots.forEach(({id, side}) => {

                    if (id !== tile.id) {
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
        addedToSheet(state) {
            return (tile) => {
                let tileIds = state.tileSlots.map(({id}) => id)

                return tileIds.indexOf(tile.id) !== -1
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
                    tile: state.tilesById[id],
                    side
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

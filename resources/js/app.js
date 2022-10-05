import './bootstrap'

import Vue from 'vue'
import Vuex from 'vuex';
import tileCrud from './store/tile-crud'
import tileSheetStore from './store/tile-sheet'
import AppTileEdit from './components/app-tile-edit'
import AppTileGrid from './components/app-tile-grid'
import AppTileView from './components/app-tile-view'
import AppTilePrint from './components/tile-print'
import AppTileSheet from './components/app-tile-sheet-create'
import {tileSlotStores} from './store/tile-sheet-slot-instances'
import routes from './app-tile-edit-routes';
import VueRouter from 'vue-router'

ifIdExists('app-tile-edit', (el) => {

    const router = new VueRouter({
        routes,
    })
    const store  = new Vuex.Store(tileCrud)
    new Vue({
        store,
        router,
        render: h => h(AppTileEdit),
    }).$mount(el)
})

ifIdExists('app-tile-grid', (el) => {
    new Vue({
        render: h => h(AppTileGrid),
    }).$mount(el)
})

ifIdExists('app-tile-view', function (el) {
    const store = new Vuex.Store(tileCrud)
    new Vue({
        el,
        store,
        components: {AppTileView},
    })
})

ifIdExists('app-tile-sheet-create', function (el) {

    tileSlotStores.forEach((store, index) => {

        let slotNumber = index + 1
        ifIdExists('app-tile-sheet-create-slot-' + slotNumber, function (el) {

            new Vue({
                store,
                render: h => h(AppTilePrint),
            }).$mount(el)
        })
    })

    new Vue({
        store: tileSheetStore,
        render: h => h(AppTileSheet),
    }).$mount(el)

})

function ifIdExists(id, callback) {

    let el = document.getElementById(id)
    if (el) {
        callback(el)
    }
}

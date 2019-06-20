import './bootstrap'

import Vue from 'vue'
import store from './store'
import AppTileEdit from './components/app-tile-edit'
import AppTileGrid from './components/app-tile-grid'
import AppTileView from './components/app-tile-view'
import routes from './app-tile-edit-routes';
import VueRouter from 'vue-router'

ifIdExists('app-tile-edit', (el) => {

    const router = new VueRouter({
        routes,
    })

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
    new Vue({
        el,
        store,
        components: { AppTileView },
    })
})

function ifIdExists(id, callback) {
    let el = document.getElementById(id)
    if (el) {
        callback(el)
    }
}

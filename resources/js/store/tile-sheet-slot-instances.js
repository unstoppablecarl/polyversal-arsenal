import Vuex from "vuex";
import tileCrud from "./tile-crud";

export const store1 = new Vuex.Store(tileCrud)
export const store2 = new Vuex.Store(tileCrud)
export const store3 = new Vuex.Store(tileCrud)
export const store4 = new Vuex.Store(tileCrud)
export const store5 = new Vuex.Store(tileCrud)

export const tileSlotStores = [
    store1,
    store2,
    store3,
    store4,
    store5
]

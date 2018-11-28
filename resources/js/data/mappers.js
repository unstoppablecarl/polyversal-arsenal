import {createNamespacedHelpers} from 'vuex';

function mapTileProperties(map) {
    return _.mapValues(map, (tile_key) => {
        return {
            get() {
                return this.$store.state.tile[tile_key];
            },
            set(val) {
                this.$store.dispatch('tile/update', {[tile_key]: val});
            },
        };
    });
}

const {
          mapGetters: mapTileGetters,
          mapActions: mapTileActions,
      } = createNamespacedHelpers('tile');

const {
          mapGetters: mapAbilityGetters,
          mapActions: mapAbilityActions,
      } = createNamespacedHelpers('abilities');


const {
          mapGetters: mapTileWeaponGetters,
          mapActions: mapTileWeaponActions,
      } = createNamespacedHelpers('tile_weapons');


export {
    mapTileProperties,
    mapTileActions,
    mapTileGetters,
    mapAbilityActions,
    mapAbilityGetters,
    mapTileWeaponActions,
    mapTileWeaponGetters,
};

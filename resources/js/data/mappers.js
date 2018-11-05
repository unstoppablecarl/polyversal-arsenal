function mapTileProperties(map) {
    return _.mapValues(map, (tile_key) => {
        return {
            get() {
                return this.$store.getters.tile[tile_key];
            },
            set(val) {
                this.$store.dispatch('updateTile', {[tile_key]: val});
            },
        };
    });
}

export {
    mapTileProperties,
};

function mapActions(keys) {
    return mapKeys(keys, mapAction);
}

function mapAction(key) {
    return ({commit}, value) => {
        commit(key, value);
        return Promise.resolve();
    };
}

function mapMutations(keys) {
    return mapKeys(keys, mapMutation);
}

function mapMutation(key) {
    return ({state}, value) => state[key] = value;
}

function mapGetters(keys) {
    return mapKeys(keys, mapGetter);
}

function mapGetter(key) {
    return (state) => state[key];
}

function mapKeys(keys, callback) {
    const result = {};

    keys.forEach((key) => {
        result[key] = callback(key);
    });

    return result;
}

export {
    mapGetter,
    mapGetters,

    mapMutation,
    mapMutations,

    mapAction,
    mapActions,
};

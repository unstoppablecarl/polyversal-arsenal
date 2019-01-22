function defActions(keys) {
    return defKeys(keys, defAction);
}

function defAction(key) {
    return ({commit}, value) => {
        commit(key, value);
        return Promise.resolve();
    };
}

function defMutations(keys) {
    return defKeys(keys, defMutation);
}

function defMutation(key) {
    return (state, value) => state[key] = value;
}

function defGetters(keys) {
    return defKeys(keys, defGetter);
}

function defGetter(key) {
    return (state) => state[key];
}

function defKeys(keys, callback) {
    const result = {};

    keys.forEach((key) => {
        result[key] = callback(key);
    });

    return result;
}

export {
    defGetter,
    defGetters,

    defMutation,
    defMutations,

    defAction,
    defActions,
};

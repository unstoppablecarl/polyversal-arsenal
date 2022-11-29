export function defAction(method) {
    return ({commit}, value) => {
        commit(method, value);
        return Promise.resolve();
    };
}

export function defActions(methods) {
    let out = {};
    methods.forEach((method) => {
        out[method] = defAction(method)
    });
    return out;
}

export function defMutations(map) {
    return _.mapValues(map, (stateKey) => defMutation(stateKey))
}

export function defMutation(stateKey) {
    return (state, value) => state[stateKey] = value;
}

export function defGetter(stateKey) {
    return (state) => state[stateKey];
}

export function defGetters(map) {
    let out = {};
    map.forEach((stateKey) => {
        out[stateKey] = defGetter(stateKey)
    });
    return out;
}

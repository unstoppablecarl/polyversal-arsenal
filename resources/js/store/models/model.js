import _ from 'lodash';

export default function (defaults) {

    const validKeys = Object.keys(defaults);

    return function (data) {
        let parsed = _.pick(data, validKeys);
        return Object.assign({}, defaults, parsed);
    };
}

import {isString, pick} from 'lodash';

export {
    makeModel,
    castInt,
};

function castInt(value) {
    if (isString(value)) {
        return parseInt(value, 10);
    }
    return value;
}

function makeModel({defaults, formatters}) {

    const validKeys = Object.keys(defaults);

    function factory(data) {
        return Object.assign({}, defaults, data);
    }

    function filterKeys(data) {
        return pick(data, validKeys);
    }

    function formatValues(data) {

        Object.keys(formatters)
            .forEach((key) => {
                let value = data[key];
                if (value !== undefined) {
                    let formatter = formatters[key];

                    data[key] = formatter(value);
                }
            });

        return data;
    }

    function make(data) {
        data = filterKeys(data);
        data = factory(data);
        data = formatValues(data);
        return data;
    }

    function sanitize(data) {
        data = filterKeys(data);
        data = formatValues(data);
        return data;
    }

    return {
        make,
        sanitize,
        formatValues,
        filterKeys,
    };
}

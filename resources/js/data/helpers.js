
function toIdDisplayName(items) {
    return toIdValue(items, 'display_name')
}


function toIdValue(items, key) {
    let out = {};
    items.forEach((row) => {
        out[row.id] = row[key];
    });
    return out;
}

export {
    toIdDisplayName,
    toIdValue
}

import {extend, findIndex} from 'lodash';
import {make as makeTileWeapon} from '../store/models/tile-weapon';

export {
    findItemById,
    findItemIndexById,
    findItemIndex,
    createItem,
    copyItem,
    updateItem,
    replaceItem,
    deleteItem,
    moveItem,
    move,
};

function findItemById(items, id) {
    let index = findItemIndexById(items, id);
    if (index === false) {
        throw Error('Item not found with id', id);
    }
    return items[index];
}

function findItemIndexById(items, id) {
    let index = findIndex(items, ['id', id]);
    if (index === -1) {
        return false;
    }
    return index;
}

function findItemIndex(items, item) {
    return findItemIndexById(items, item.id);
}

function findItemIndexOrFail(items, item) {
    let index = findItemIndex(items, item);
    if (index === false) {
        console.error('item not found', item);
        throw Error('Item not found', item);
    }
    return index;
}

function replaceItem(items, item) {
    let index = findItemIndexOrFail(items, item);

    items.splice(index, 1, item);
}

function updateItem(items, item) {

    let index   = findItemIndexOrFail(items, item);
    let current = items[index];
    let updated = extend({}, current, item);

    items.splice(index, 1, updated);
}

function deleteItem(items, item) {
    let index = findItemIndexOrFail(items, item);

    items.splice(index, 1);
    setDisplayOrders(items);
}

function moveItem(items, item, toIndex) {
    let index = findItemIndexOrFail(items, item);
    move(items, index, toIndex);
}

function move(items, fromIndex, toIndex) {
    let item = items.splice(fromIndex, 1)[0];
    items.splice(toIndex, 0, item);
    setDisplayOrders(items);
}


function copyItem(items, item) {
    let index   = findItemIndex(items, item);
    let toIndex = index + 1;
    createItem(items, item, toIndex);
}

function setDisplayOrders(items) {
    items.forEach((item, index) => {
        item.display_order = index;
    });
}

let newId = 1;

function createItem(items, item, newIndex) {

    item.id = 'new-' + newId++;

    if (newIndex) {
        items.splice(newIndex, 0, item);
        setDisplayOrders(items);
    } else {
        items.push(item);
        item.display_order = findItemIndex(items, item);
    }
}

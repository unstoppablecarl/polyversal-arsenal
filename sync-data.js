import fs from 'fs';
import {basic} from './resources/js/data/weapons';

//Object.keys(basic).forEach((key) => {
//
//    let item = basic[key];
//
//});

let json = JSON.stringify(basic);

fs.writeFile('./database/data/weapons.json', json, 'utf8', function () {
    console.log('z');
});


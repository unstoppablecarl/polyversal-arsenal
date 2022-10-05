import fs from 'fs';
import PDFDocument from 'pdfkit'

let blobStream = require('blob-stream');
import {fetchFile} from './tile-sheet-pdf-helpers';

// units are in pdf points 1 inch = 72 pdf points
const pageHeight = 792;
const pageWidth  = 612;

const top    = 48.0024;
const right  = 77.6448;
const bottom = 48.0024;
const left   = right;

const tileWidth  = 249.4152;
const tileHeight = 216;

const verticalSpacing = 23.976;

const hex1 = {
    x: left,
    y: top
}

const hex2 = {
    x: left,
    y: top + tileHeight + verticalSpacing
}

const hex3 = {
    x: left,
    y: top + ((tileHeight + verticalSpacing) * 2)
}

const hex4 = {
    x: pageWidth - right - tileWidth,
    y: top + (tileHeight * 0.5) + (verticalSpacing * 0.5)
}

const hex5 = {
    x: hex4.x,
    y: hex4.y + tileHeight + verticalSpacing
}

const coords = [
    hex1,
    hex2,
    hex3,
    hex4,
    hex5
]

const hexCoords = [
    [62.352, 0],
    [187.0632, 0],
    [249.4152, 108],
    [187.0632, 216],
    [62.352, 216],
    [0, 108],
];


export function makeBase64Pdf(images) {

    return loadImages(images)
        .then(() => {

            const doc = new PDFDocument({size: 'LETTER'});

            drawImages(doc, images)

            return docToBase64URL(doc)

        })
        .catch((err) => {
            throw err
        })
}

function drawImages(doc, images) {

    doc.moveTo(0, top)
        .lineTo(pageWidth, top)
        .stroke()

    doc.moveTo(left, 0)
        .lineTo(left, pageHeight)
        .stroke()

    doc.moveTo(pageWidth - right, 0)
        .lineTo(pageWidth - right, pageHeight)
        .stroke()

    doc.moveTo(0, pageHeight - bottom)
        .lineTo(pageWidth, pageHeight - bottom)
        .stroke()

    images.forEach((image, index) => {
        let pos = coords[index];

        let marginX = (72 * .2);
        let marginY = (72 * .2);

        let offsetX = (marginX * 0.5);
        let offsetY = (marginY * 0.5);

        let absoluteTileHeight = 215

        let manualOffsetX = 0.4
        offsetX += manualOffsetX;

        doc.image(
            image,
            pos.x - offsetX,
            pos.y - offsetY,
            {height: absoluteTileHeight + marginY}
        );

        drawHex(doc, pos.x, pos.y);
    })
}

function docToBase64URL(doc) {

    const stream = doc.pipe(blobStream());
    return new Promise((resolve, reject) => {
        stream.on('finish', () => {

            let url = stream.toBlobURL('application/pdf');

            resolve(url)
        });

        stream.on('error', (err) => {
            reject(err)
        })

        doc.end();

    })
}

function drawHex(doc, x, y) {

    let coords = hexCoords.map((pos) => {
        return [
            pos[0] + x,
            pos[1] + y
        ]
    });

    doc.polygon(...coords)
        .strokeColor('#0000ff')
        .strokeOpacity(0.4)
        .stroke()
}

function loadImages(images) {

    let promises = images.map((image) => {
        return fetchFile(image)
            .then(imageData => {
                fs.writeFileSync(image, imageData);
            })
            .catch(error => {
                console.error(error);
            })
    })

    return Promise.all(promises)
}

export default function svgToPngBase64(svgId, width, height) {
    return new Promise((resolve, reject) => {

        let svg     = document.getElementById(svgId);
        let svgData = new XMLSerializer().serializeToString(svg);

        let canvas = document.createElement('canvas');

        canvas.width  = width;
        canvas.height = height;

        let ctx = canvas.getContext('2d');
        let img = document.createElement('img');

        img.setAttribute('src', 'data:image/svg+xml;base64,' + btoa(svgData));

        img.onload = function () {
            ctx.drawImage(img, 0, 0, width, height);
            let dataURL = canvas.toDataURL('image/png');
            resolve(dataURL);
        };

    });
}


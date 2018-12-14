function toDataURL(src, outputFormat) {
    return new Promise((resolve, reject) => {
        var img         = new Image();
        img.crossOrigin = 'Anonymous';
        img.onload      = function () {
            var canvas    = document.createElement('CANVAS');
            var ctx       = canvas.getContext('2d');
            var dataURL;
            canvas.height = this.naturalHeight;
            canvas.width  = this.naturalWidth;
            ctx.drawImage(this, 0, 0);
            dataURL = canvas.toDataURL(outputFormat);
            resolve(dataURL);
        };

        img.src = src;
    });

}

export default toDataURL;

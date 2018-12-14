export default function downloadDataURL(dataURL, fileName) {
    var downloadLink      = document.createElement('a');
    downloadLink.href     = dataURL;
    downloadLink.download = fileName;
    document.body.appendChild(downloadLink);
    downloadLink.click();
    document.body.removeChild(downloadLink);
}


export default function svgToBase64(svgId) {
    let svgEl   = document.getElementById(svgId);
    var svgData = svgEl.outerHTML;
    var preface = '<?xml version="1.1" standalone="no"?>\r\n';
    var svgBlob = new Blob([preface, svgData], {type: 'image/svg+xml;charset=utf-8'});
    return URL.createObjectURL(svgBlob);
}



export default svgToBase64;

function svgToBase64(svgId, fileName) {
    let svgEl = document.getElementById(svgId);

    svgEl.setAttribute("xmlns", "http://www.w3.org/2000/svg");
    var svgData = svgEl.outerHTML;

    svgData = svgData.replace(new RegExp('svg\:style', 'g'), 'style');
    var preface = '<?xml version="1.0" standalone="no"?>\r\n';
    var svgBlob = new Blob([preface, svgData], {type:"image/svg+xml;charset=utf-8"});
    return URL.createObjectURL(svgBlob);
}



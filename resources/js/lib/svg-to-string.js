export default svgToString;

function svgToString(svgId) {
    let svgEl = document.getElementById(svgId);
    svgEl.setAttribute('xmlns', 'http://www.w3.org/2000/svg');
    let svgData = svgEl.outerHTML;

    svgData = svgData.replace(new RegExp('svg\:style', 'g'), 'style');
    return svgData;
}



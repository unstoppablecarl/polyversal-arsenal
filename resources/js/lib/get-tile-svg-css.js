export default function getTileSvgCss(id) {
    let el    = document.getElementById(id);
    let rules = find(el.href);
    let out   = '';

    rules.forEach(rule => {
        out += ' ' + rule.cssText
    });

    return out;
}

function find(href) {
    let styleSheets = document.styleSheets;
    for (let i = 0; i < styleSheets.length; i++) {

        let item = styleSheets[i];

        if (item.href == href) {
            return Array.from(item.cssRules);
        }
    }
}

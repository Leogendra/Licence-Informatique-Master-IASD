function addClass(node, htmlClass) { 
    if (false === node.classList.contains(htmlClass)) {
        node.classList.add(htmlClass);
    }
}

function removeClass(node, htmlClass) {
    if (true === node.classList.contains(htmlClass)) {
        node.classList.remove(htmlClass);
    }
}

function ph_get_site_link(route) {
    return window.localStorage.siteRoot + '/' + route;
}
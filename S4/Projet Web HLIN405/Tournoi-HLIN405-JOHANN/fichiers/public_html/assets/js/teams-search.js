let filters = new Filters();

let enums = JSON.parse(window.localStorage.enums);
enums.comparator = {
    '=': 'égale à',
    '<': 'inférieure à',
    '<=': 'inférieure ou égale à',
    '>': 'supérieure à',
    '>=': 'supérieure ou égale à'
};
window.localStorage.enums = JSON.stringify(enums);

filters.addInput('name', {tag: 'input', type: 'text', class: 'form-control', name: 'name', id: 'name', placeholder: 'Nom d\'une équipe (même incomplet)'});
filters.addInput('captain', {tag: 'select', class: 'form-select', name: 'captain', children: getEnumOptionObject('captain', true)});
filters.addInput('nb-players', {tag: 'div', class:'row', children: {
    div1: {tag: 'div', class: 'col-4', children: {
        select: {tag: 'select', class: 'form-select', name: 'nb-players-comparator', children: getEnumOptionObject('comparator', true)}
    }},
    div2: {tag: 'div', class: 'col-8', children: {
        input: {tag: 'input', type: 'number', class: 'form-control', name: 'nb-players', id: 'nb-players', min: 1, value: 1} 
    }}
}});
filters.addInput('activity', {tag: 'select', class: 'form-select', name: 'activity', children: getEnumOptionObject('activity', true)});

let values = window.localStorage.searchData;

if ('undefined' === typeof values || 0 === values.length) {
    filters.addFilter();
}
else {
    values = JSON.parse(values);
    for (valueKey in values) {
        if (Array.isArray(values[valueKey])) {
            values[valueKey].forEach((value) => {
                filters.setFilterValue(valueKey, value);
            });
        }
        else  {
            filters.setFilterValue(valueKey, values[valueKey]);
        }
        filters.addFilter(valueKey);
    }
}
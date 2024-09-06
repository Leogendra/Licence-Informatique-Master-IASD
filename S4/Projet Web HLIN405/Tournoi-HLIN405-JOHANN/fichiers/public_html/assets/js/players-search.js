let filters = new Filters();

filters.addInput('name', {tag: 'input', type: 'text', class: 'form-control', name: 'name', id: 'name', placeholder: 'Nom d\'une équipe (même incomplet)'});
filters.addInput('email', {tag: 'input', type: 'text', class: 'form-control', name: 'name', id: 'name', placeholder: 'Adresse mail (même incomplète)'});
filters.addInput('role-1', {tag: 'select', class: 'form-select', name: 'role-1', children: getEnumOptionObject('roles')});
filters.addInput('role-2', {tag: 'select', class: 'form-select', name: 'role-2', children: getEnumOptionObject('roles')});
filters.addInput('role-3', {tag: 'select', class: 'form-select', name: 'role-3', children: getEnumOptionObject('roles')});

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
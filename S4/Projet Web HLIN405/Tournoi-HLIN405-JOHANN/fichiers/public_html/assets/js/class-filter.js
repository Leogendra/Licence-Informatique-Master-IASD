/**
 * Classe pour faire des filtres de recherches en javascript.
 * 
 * Pour que la classe marche, elle a besoin de 3 éléments :
 *   - Un bouton avec l'id add-filter
 *   - Un objet <select> avec toutes les options possibles, avec l'attribut style="display:none"
 *   - Un formulaire avec l'id search-form
 * Si un de ces 3 élément manque, aucun filtre ne sera créé, et une erreur s'affichera dans la console.
 * 
 * Cette classe permet aussi d'ajouter les inputs que l'on veut, grâce à la méthode addInput.
 */
 class Filters {
    constructor() {
        let addFilterButton = document.getElementById('add-filter');

        if (null === addFilterButton) {
            console.error('Le bouton pour ajouter un filter n\'est pas défini dans la page. Ajoutez un bouton avec l\'id "add-filter" pour pouvoir utiliser les fonctionnalités du script.');
            return;
        }

        this.select = document.querySelector('.search-select[style="display:none"]');

        if (null === this.select) {
            console.error('L\'objet de sélection de filtre n\'est pas instancié. Veuillez ajouter une balise <select style="display:none"> avec les options désirées.');
            return;
        }

        this.form = document.querySelector('#search-form');

        if (null === this.form) {
            console.error('Le formulaire de recherche n\'a pas pu être récupéré. Assurez-vous d\'avoir un formulaire avec l\'id "search-form".');
            return;
        }

        this.inputs = {};
        this.disabledKeys = [];
        this.filters = [];
        addFilterButton.onclick = () => { this.addFilter(); }
    }

    /**
     * Ajoute un objet input à la clé donnée.
     * 
     * @param {String} key   Chaîne correspondant à la valeur d'une option du select
     * @param {Object} input Objet avec tous les attributs que doit porter l'input
     */
    addInput(key, input) {
        this.inputs[key] = input;
    }

    /**
     * Ajoute une ligne de filtre.
     */
    addFilter(toAdd = '') {
        let selectFilter = this.select.cloneNode(true);
        selectFilter.removeAttribute('style');

        Array.from(selectFilter.options).forEach((element) => {
            if (this.disabledKeys.includes(element.value)) {
                element.disabled = true;
            }
            else if ('' === toAdd || element.value === toAdd) {
                element.selected = true;
                toAdd = element.value;
            }
        });

        if ('' !== toAdd) {
            let row = this.addFilterDiv(selectFilter, toAdd);
            this.filters.push(selectFilter);
            this.disableName(toAdd);
    
            // Au changement, disable l'option choisie chez les autres filtres.
            selectFilter.onfocus = function() {
                this.previous = this.value;
            };
            selectFilter.onchange = () => { this.updateFilter(selectFilter, row) };
        }
        else {
            console.warn('Plus de filtre à ajouter.');
        }
    }

    /**
     * Crée une division de filtre.
     * 
     * @param {Element} selectFilter Le clone de l'élément de sélection.
     * @param {String}  inputName    Le nom de l'input à ajouter.
     * @returns {Element} La division parente.
     */
    addFilterDiv(selectFilter, inputName) {
        let parentRow = document.createElement('div');
        parentRow.classList.add('row');
        parentRow.classList.add('mb-3');
        let selectCol = document.createElement('div');
        selectCol.classList.add('col-3');
        parentRow.appendChild(selectCol);
        let inputCol = document.createElement('div');
        inputCol.classList.add('col-8');
        parentRow.appendChild(inputCol);
        let deleteCol = document.createElement('div');
        deleteCol.classList.add('col-1');
        let buttonClose = document.createElement('button');
        buttonClose.setAttribute('type', 'button');
        buttonClose.classList.add('btn-close');
        deleteCol.appendChild(buttonClose);
        parentRow.appendChild(deleteCol);
        selectCol.appendChild(selectFilter);

        inputCol.appendChild(this.createFormattedNode(this.inputs[inputName]));

        this.form.insertBefore(parentRow, this.form.lastElementChild);

        buttonClose.onclick = () => {
            if (this.disabledKeys.length !== 1) {
                this.form.removeChild(parentRow);
                this.removeFilter(inputName);
            }
        };

        return parentRow;
    }

    /**
     * Crée une node et met tous les attributs à la valeur de ceux stockés dans l'objet.
     * 
     * @param {Object} inputData Nom à aller chercher dans les inputs ajoutés. 
     * @returns {Element} Élément DOM correspondant à l'objet à la clé inputName.
     */
    createFormattedNode(inputData) {
        let input = document.createElement(inputData.tag);

        let doNotSet = ['tag', 'children', 'innerHTML'];

        for (let attribute in inputData) {
            if (false === doNotSet.includes(attribute)) {
                input.setAttribute(attribute, inputData[attribute]);
            }
            else if ('innerHTML' === attribute) {
                input.innerHTML = inputData[attribute];
            }
        }

        for (let child in inputData.children) {
            let childNode = this.createFormattedNode(inputData.children[child]);
            input.appendChild(childNode);
        }

        return input;
    }

    /**
     * Le filtre a été enlevé, enlève ainsi la clé de l'élément des éléments à désactiver et met
     * à jour toutes les balises select.
     * 
     * @param {String} inputName Nom de l'entrée à enlever. 
     */
    removeFilter(inputName) {
        this.disabledKeys.splice(this.disabledKeys.indexOf(inputName), 1);
        this.filters.forEach((select) => {
            Array.from(select.options).forEach((option) => {
                if (option.value === inputName) {
                    option.disabled = false;
                }
            });
        });
    }

    /**
     * Désactive une option pour pas que le filtre associé puisse être mis plusieurs fois.
     * 
     * @param {String} inputName Le nom de l'option à désactiver 
     */
    disableName(inputName) {
        this.disabledKeys.push(inputName);
        this.filters.forEach((select) => {
            Array.from(select.options).forEach((option) => {
                if (select.value !== inputName && option.value === inputName) {
                    option.disabled = true;
                }
            });
        });
    }

    /**
     * Met à jour le filtre avec le nouvel input.
     * 
     * @param {Element} select L'élément DOM correspondant au SELECT à mettre à jour. 
     * @param {Element} row    L'élément DOM qui contient l'input à mettre à jour.
     */
    updateFilter(select, row) {
        this.removeFilter(select.previous);
        this.disableName(select.value);

        let inputDiv = row.querySelector('.col-8');
        Array.from(inputDiv.children).forEach(n => n.remove());
        inputDiv.appendChild(this.createFormattedNode(this.inputs[select.value]));
    }

    setFilterValue(filterKey, value) {
        let obj = this.inputs[filterKey];

        obj = this.setFilterValueAux(obj, value);

        this.inputs[filterKey] = obj;
    }

    setFilterValueAux(obj, value) {
        if ('select' === obj.tag) {
            for (let opt in obj.children) {
                if (value === obj.children[opt].value || value === obj.children[opt].innerHTML) {
                    obj.children[opt].selected = true;
                }
            }
        }
        else if ('input' === obj.tag) {
            obj.value = value;
        }
        else {
            for (let child in obj.children) {
                obj.children[child] = this.setFilterValueAux(obj.children[child], value);
            }
        }

        return obj;
    }
}

/**
 * Ajoute une méthode à la classe Date pour formatter une date comme attendue en HTML. 
 */
Date.prototype.formatString = function() {
    let day   = '' + this.getDate();
    let month = '' + (this.getMonth() + 1);
    let year  = this.getFullYear();

    if (month.length < 2) month = '0' + month;
    if (day.length < 2) day = '0' + day;

    return [year, month, day].join('-');
}

/**
 * Récupère une énumération et fabrique des objets option avec
 */
function getEnumOptionObject(key, setValue = false, disable = [], select = '') {
    let obj = JSON.parse(window.localStorage.enums)[key];

    let enumObj = {};

    for (let opt in obj) {
        let tmpObj = {tag: 'option', innerHTML: obj[opt]};
        if (true === setValue) {
            tmpObj.value = opt;
        }
        if (disable.includes(opt)) {
            tmpObj.disabled = true;
        }
        if (select === opt) {
            tmpObj.selected = true;
        }
        enumObj['opt' + opt] = tmpObj;
    }

    return enumObj;
}
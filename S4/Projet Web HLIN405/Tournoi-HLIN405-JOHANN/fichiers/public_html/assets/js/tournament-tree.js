/**
 * Va chercher les résultats du match parent pour savoir qui est le vainqueur à mettre dans le champ
 * Cette fonction se fait en récursion et va remplir tous les champs de l'arbre du tournoi dès que
 * le manager modifie un élément.
 * 
 * @param {DOMElement} teamEl    La team dont on veut le résultat 
 * @param {boolean} fillValues   true si on veut que les champs se remplissent aussi, défaut à false
 * @returns array avec comme première valeur l'id de l'équipe gagnante, en seconde le nom de l'équipe gagnante et en troisième son image de profil
 */
function getResultFor(teamEl, fillValues = false) {
    if (!teamEl.hasAttribute('data-parent')) {
        let select = teamEl.querySelector('.team-select');
        let imgSrc = teamEl.querySelector('.team-pp').src;
        let selectedId = select.value;
        let selectedTeam = select.options[select.selectedIndex].text;
        return [selectedId, selectedTeam, imgSrc];
    }

    let parentMatchId = teamEl.getAttribute('data-parent');
    let parentMatch = document.getElementById('match-' + parentMatchId);

    let team1Field = parentMatch.querySelector('.team-1');
    let team2Field = parentMatch.querySelector('.team-2');

    let team1Result = getResultFor(team1Field, true);
    let team2Result = getResultFor(team2Field, true);

    // Valeur par défaut si égalité
    let matchResult = [0, '', ph_get_site_link('assets/resources/no-team-pp.png')];
    
    // On ne continue l'arbre que si les deux équipes sont dans le match
    if (team1Result[1] !== '' && team2Result[1] !== '') {
        let team1Score = team1Field.querySelector('.team-1-result').value;
        let team2Score = team2Field.querySelector('.team-2-result').value;

        if (team1Score > team2Score) {
            matchResult = team1Result;
        }
        else if (team2Score > team1Score) {
            matchResult = team2Result;
        }
    }

    if (fillValues) {
        let teamIdField = teamEl.querySelector('.automatic-team-id');
        let teamNameField = teamEl.querySelector('.automatic-team-name');
        let teamPPField = teamEl.querySelector('.team-pp');

        teamIdField.value = matchResult[0];
        teamNameField.value = matchResult[1];
        teamPPField.src = matchResult[2];
    }
    
    return matchResult;
}

/**
 * Reconstruit en entier l'arbre, en partant de la racine
 */
function rebuildTree() {
    winner = document.getElementById('winner');
    result = getResultFor(winner);

    let winnerNameField = winner.querySelector('.team-name');
    let winnerPPField = winner.querySelector('.team-pp');

    winnerNameField.innerHTML = result[1];
    winnerPPField.src = result[2];
}

/**
 * Quand l'un des select d'équipe change, on veut que l'ancienne valeur aille dans le select
 * qui avait la nouvelle valeur.
 * On fait ça car une équipe n'est qu'à un seul endroit à la fois. Ainsi, toutes les équipes seront
 * toujours dans l'arbre.
 * 
 * @param {DOMElement} select Le select qui a été modifié par le manager 
 */
function onTeamChange(select) {
    let values = [];
    let toChange = null;

    Array.from(document.getElementsByClassName('team-select')).forEach(element => {
        if (element !== select) {
            if (element.value === select.value) {
                toChange = element;
            }

            values.push(element.value);
        }
    });

    if (null !== toChange) {
        Array.from(select.options).forEach(option => {
            if (!values.includes(option.value)) {
                toChange.value = option.value;
            }
        });

        // On switch les images de profil
        selectImg = select.closest('.team-wrapper').querySelector('.team-pp');
        toChangeImg = toChange.closest('.team-wrapper').querySelector('.team-pp');
    
        selectImgSrc = selectImg.src;
        toChangeImgSrc = toChangeImg.src;
    
        selectImg.src = toChangeImgSrc;
        toChangeImg.src = selectImgSrc;
    }

    rebuildTree();
}

/**
 * Les champs de résultat étant des champs de texte, on vérifie que la valeur est bien un nombre.
 * 
 * @param {DOMElement} resultField Le champs texte ou le résultat a été modifié 
 */
function onResultChange(resultField) {
    if (isNaN(resultField.value)) {
        resultField.value = 0;
    }
    else {
        resultField.value = Number(resultField.value);
    }

    rebuildTree();
}

/**
 * Les champs dates modifiés doivent également modifier la date affichée dans l'arbre
 * 
 * @param {DOMElement} dateField Le champ date qui a été modifié 
 */
function onDateChange(dateField) {
    let modal = dateField.closest('.modal');
    let id = modal.getAttribute('id');
    let dateButton = document.querySelector('button[data-bs-target="#' + id + '"]');

    let date = new Date(dateField.value);
    let month = date.toLocaleDateString('en', { month: '2-digit' });
    let day = date.toLocaleDateString('en', { day: '2-digit' });

    dateButton.innerHTML = day + '/' + month;
}

/**
 * Remet l'arbre à 0, sauf pour l'ordre des équipes
 */
function resetTree() {
    Array.from(document.getElementsByClassName('team-result')).forEach(field => {
        field.value = 0;
    });
    rebuildTree();
}

/**
 * Mélange les équipes et les scores pour faire un arbre complètement aléatoire
 */
function shuffleTree() {
    shuffleTeam();
    shuffleScore(0, 10);
    rebuildTree();
}

/**
 * Mélange toutes les équipes en faisant attention de bien mettre les image de profil
 */
function shuffleTeam() {
    let values = [];
    let selects = Array.from(document.getElementsByClassName('team-select'));

    selects.forEach(select => {
        values.push({
            option: select.value,
            src: select.closest('.team-wrapper').querySelector('.team-pp').src
        });
    });
    
    for (let i = values.length - 1; i > 0; i--) { 
        let j = Math.floor(Math.random() * (i + 1));
                    
        let temp = values[i];
        values[i] = values[j];
        values[j] = temp;
    }

    selects.forEach(select => {
        let value = values.shift();
        select.value = value.option;
        select.closest('.team-wrapper').querySelector('.team-pp').src = value.src;
    });
}

/**
 * Mets tous les scores aléatoirement dans la range [min, max]
 * 
 * @param {int} min Le minimum du score 
 * @param {int} max Le maximum du score, doit être strictement supérieur à min
 */
function shuffleScore(min, max) {
    Array.from(document.querySelectorAll('.match-wrapper:not(.no-match)')).forEach(match => {
        let fields = match.getElementsByClassName('team-result');
        fields[0].value = Math.floor(Math.random() * (min + max + 1)) + min;
        do {
            fields[1].value = Math.floor(Math.random() * (min + max + 1)) + min;
        }
        while (fields[0].value === fields[1].value);
    });
}
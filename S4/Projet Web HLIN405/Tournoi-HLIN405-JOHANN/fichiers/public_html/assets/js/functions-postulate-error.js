/**
 * Affiche une erreur dans la div prévue à cet effet.
 * 
 * @param {string} error L'erreur à afficher
 * 
 * @author Benoît Huftier
 */
 function ph_display_postulate_error(error) {
    let error_div = document.getElementById('error-div');
    let error_text = document.querySelector('#error-div .error-text');
    error_div.classList.remove('d-none');
    error_text.innerHTML = 'Erreur : ' + error;
}

/**
 * Cache la div d'erreur
 * 
 * @author Benoît Huftier
 */
function ph_hide_postulate_error() {
    document.getElementById('error-div').classList.add('d-none');
}

/**
 * Pour l'erreur qui s'affiche, le close ne supprime pas la div
 */
 document.getElementById('error-close-button').addEventListener('click', ph_hide_postulate_error);
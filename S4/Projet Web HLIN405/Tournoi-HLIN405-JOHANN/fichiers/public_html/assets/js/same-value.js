/*
 * Ce fichier est à inclure pour pouvoir effectuer une vérification de correspondance entre 2 
 * balises de type input. Afin que les fonctions soient correctement appelées, il faut faire 
 * un code html qui ressemble à ceci :
 * ```html
 * <label for="password">Mot de passe</label>
 * <input type="password" name="password" id="password" />
 * <label for="password-verif">Mot de passe (vérification)</label>
 * <input type="password" for="password" id="password-verif" class="same-value" />
 * ``` 
 * De cette façon, le 2ème input doit correspondre au premier, et inversement.
 */

/**
 * Vérifie si `monitored` et `monitor` ont le même texte.
 * S'ils ont le même texte, `monitored` va devenir valide, et le bouton du formulaire va être activé.
 * Sinon, il va devenir invalide, et une div pour afficher pourquoi c'est invalide. Le bouton va être désactivé.
 * 
 * @param {Element} monitored 
 * @param {Element} monitor 
 * @param {Element} button 
 * 
 * @author Johann Rosain
 */
function checkSame(monitored, monitor, button) {
    if (monitored.value !== monitor.value) {
        addClass(monitored, "is-invalid");
        removeClass(monitored, "is-valid");
        button.setAttribute("disabled", true);
    } 
    else {
        addClass(monitored, "is-valid");
        removeClass(monitored, "is-invalid");
        button.removeAttribute("disabled");
    }
}

/**
 * Récupère :
 *   - L'élément de l'attribut `for` d'`inputNode`
 *   - Le bouton de soumission.
 * Si un des deux éléments n'existe pas, une erreur est affichée dans la console, et la fonction s'arrête là.
 * Sinon, les 2 éléments de type input sont surveillés à chaque frappe pour voir si le contenu des deux correspond.
 * 
 * De plus, crée une div html avec le message « les valeurs diffèrent ! ». Cette div est affichée via `checkSame`.
 * 
 * @param {Element} inputNode 
 * 
 * @author Johann Rosain
 * @see    checkSame
 */
function onChangeCheckSame(inputNode) {
    let monitoredField = document.querySelector("#" + inputNode.getAttribute("for"));
    let submitButtons  = document.querySelectorAll('[type="submit"]');
    let submitButton   = Array.from(submitButtons).filter(function(element) { return element.form === inputNode.form })[0];

    if (undefined === monitoredField || undefined === submitButton) {
        console.error(new Error("Le formulaire n'est pas correctement formé. Il manque le bouton de soumission ou bien le `for` dans l'élément .same-value."));
        return;
    }

    inputNode.addEventListener("input", function() {
        checkSame(this, monitoredField, submitButton);
    });
    monitoredField.addEventListener("input", function() {
        checkSame(inputNode, this, submitButton);
    });

    let element = document.createElement("div");
    element.innerHTML = "Les valeurs diffèrent !";
    element.classList.add("invalid-feedback");
    inputNode.parentNode.insertBefore(element, inputNode.nextSibling);
}

document.addEventListener("DOMContentLoaded", function(event) {
    document.querySelectorAll(".same-value").forEach(function(node) {
        onChangeCheckSame(node);
    });
});
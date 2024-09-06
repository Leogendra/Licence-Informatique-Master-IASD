/**
 * Envoie une validation spécifique au serveur via la fonction fetch
 * 
 * @param {string} validation_page Le nom de la page de validation. Elle doit écrire un document json lisible pour être valide
 * @param {object} datas           Les données à envoyer à la page de validation, sous forme d'objet
 * @param {callback} on_success    La fonction à faire lorsque la validation réussie, elle peut prendre un paramètre de type string qui est le message renvoyée.
 * @param {callback} on_fail       La fonction à faire lorsque la validation échoue, elle peut prendre un paramètre de type string qui est l'erreur renvoyée.
 * 
 * @author Benoît Huftier
 */
 function ph_send_validation(validation_page, datas, on_success, on_fail) {
    fetch(ph_get_site_link(validation_page), {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: new URLSearchParams(datas).toString()
    })
    .then(response => response.json())
    .then(json => {
        if ('undefined' !== typeof json['success'] && 'undefined' !== typeof json['message']) {
            if (true === json['success']) {
                on_success(json['message']);
            }
            else {
                on_fail(json['message']);
            }
        }
        else {
            on_fail('La réponse du serveur n\'est pas normale');
        }
    })
    .catch(on_fail);
}
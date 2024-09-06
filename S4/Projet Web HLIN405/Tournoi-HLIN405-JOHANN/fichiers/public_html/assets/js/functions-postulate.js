function ph_get_actual_team_id() {
    return window.location.pathname.split('/').pop();
}

/**
 * Supprime la colonne et affiche le message de la BDD lorsque la validation est bonne
 * 
 * @param {string} message     Le message renvoyé par le serveur.
 * @param {int} player_user_id L'id utilisateur du joueur modifié.
 * 
 * @author Benoît Huftier
 */
function ph_delete_player_row(message, player_user_id) {
    // Suppression des anciens messages pour qu'il n'y en ai pas trop
    document.querySelectorAll('.message-div').forEach(e => e.remove());

    let row = document.getElementById('postulate-row-player-' + player_user_id);
    row.classList.remove('d-flex');
    row.classList.add('message-div');
    row.innerHTML = '<p class="text-center" style="color: green;">' + message + '</p>';
}

/**
 * Envoie une validation spécifique au serveur via la fonction fetch
 * 
 * @param {string} validation_page Le nom de la page de validation, elle doit se situer dans '/validation/team/'. 
 * @param {int} player_user_id     L'id de l'utilisateur à accepter/refuser/éjecter/blocker...
 * @param {callback} on_success    La fonction à faire lorsque la validation est passée, elle peut prendre deux paramètres de type string,
 *                                 le message renvoyé par le serveur et l'identifiant du joueur. 
 * @param {callback} on_fail       La fonction à faire lorsque la validation échoue, elle peut prendre un paramètre de type string qui est l'erreur renvoyée.
 * 
 * @author Benoît Huftier
 */
function ph_send_postulate_validation(validation_page, player_user_id, on_success, on_fail) {
    datas = {
        team_id: ph_get_actual_team_id(),
        player_user_id: player_user_id
    };
    ph_send_validation('validation/team/' + validation_page, datas, message => on_success(message, player_user_id), on_fail)
}

/**
 * Fonction de callback lorsqu'un joueur a été éjecté
 * 
 * @author Benoit Huftier
 */
function ph_eject_player_success(message, player_user_id) {
    ph_delete_player_row(message, player_user_id);

    // On reload les joueurs et les joueurs bloqués, car ils ont été modifiés
    $('#players').load(window.location.href + ' #players>*');
    $('#blocked-players').load(window.location.href + ' #blocked-players>*');
}

/**
 * Fonction de callback lorsqu'un joueur a été accepté
 * 
 * @author Benoit Huftier
 */
function ph_accept_player_success(message, player_user_id) {
    ph_delete_player_row(message, player_user_id);

    // On reload les joueurs car ils ont été modifiés
    $('#players').load(window.location.href + ' #players>*');
    $('#actual-players').load(window.location.href + ' #actual-players>*');
}

/**
 * Fonction de callback lorsqu'un joueur a été refusé
 * 
 * @author Benoit Huftier
 */
function ph_refuse_player_success(message, player_user_id) {
    ph_delete_player_row(message, player_user_id);

    // On reload les joueurs bloqués car ils ont été modifiés
    $('#blocked-players').load(window.location.href + ' #blocked-players>*');
}

/**
 * Fonction qui gère les éjections de joueurs de l'équipe par le capitaine.
 * 
 * @author Benoît Huftier
 */
function ph_eject_player(player_user_id) {
    ph_send_postulate_validation('remove-player', player_user_id, ph_eject_player_success, ph_display_postulate_error);
}

/**
 * Fonction qui gère les acceptations de joueurs dans l'équipe par le capitaine.
 * 
 * @author Benoît Huftier
 */
function ph_accept_player(player_user_id) {
    ph_send_postulate_validation('accept-player', player_user_id, ph_accept_player_success, ph_display_postulate_error);
}

/**
 * Fonction qui gère les refus de joueurs dans l'équipe par le capitaine.
 * 
 * @author Benoît Huftier
 */
function ph_refuse_player(player_user_id) {
    ph_send_postulate_validation('refuse-player', player_user_id, ph_refuse_player_success, ph_display_postulate_error);
}

/**
 * Fonction qui ajoute un joueur bloqué dans l'équipe.
 * 
 * @author Benoît Huftier
 */
function ph_accept_refused_player(player_user_id) {
    ph_send_postulate_validation('accept-refused-player', player_user_id, ph_accept_player_success, ph_display_postulate_error);
}

/**
 * Fonction qui donne la possibilité à un joueur bloqué de repostuler.
 * 
 * @author Benoît Huftier
 */
function ph_unblock_player(player_user_id) {
    ph_send_postulate_validation('unblock-player', player_user_id, ph_delete_player_row, ph_display_postulate_error);
}
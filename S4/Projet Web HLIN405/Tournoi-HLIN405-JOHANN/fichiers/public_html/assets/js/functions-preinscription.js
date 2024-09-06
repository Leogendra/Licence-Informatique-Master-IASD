function ph_get_actual_tournament_id() {
    return window.location.pathname.split('/').pop();
}

/**
 * Supprime la colonne et affiche le message de la BDD lorsque la validation est bonne
 * 
 * @param {string} message     Le message renvoyé par le serveur.
 * @param {int} team_id        L'id de l'équipe modifiée.
 * 
 * @author Benoît Huftier
 */
function ph_delete_team_row(message, team_id) {
    // Suppression des anciens messages pour qu'il n'y en ai pas trop
    document.querySelectorAll('.message-div').forEach(e => e.remove());

    let row = document.getElementById('postulate-row-team-' + team_id);
    row.classList.remove('d-flex');
    row.classList.add('message-div');
    row.innerHTML = '<p class="text-center" style="color: green;">' + message + '</p>';
}

/**
 * Envoie une validation spécifique au serveur via la fonction fetch
 * 
 * @param {string} validation_page Le nom de la page de validation, elle doit se situer dans '/validation/tournament-management/'. 
 * @param {int} team_id            L'id de l'équipe à accepter/refuser/éjecter/blocker...
 * @param {callback} on_success    La fonction à faire lorsque la validation est passée, elle peut prendre deux paramètres de type string,
 *                                 le message renvoyé par le serveur et l'identifiant de l'équipe. 
 * @param {callback} on_fail       La fonction à faire lorsque la validation échoue, elle peut prendre un paramètre de type string qui est l'erreur renvoyée.
 * 
 * @author Benoît Huftier
 */
function ph_send_preinscription_validation(validation_page, team_id, on_success, on_fail) {
    datas = {
        tournament_id: ph_get_actual_tournament_id(),
        team_id: team_id
    };
    ph_send_validation('validation/tournament-management/' + validation_page, datas, message => on_success(message, team_id), on_fail)
}

/**
 * Fonction de callback lorsqu'une équipe a été éjectée
 * 
 * @author Benoit Huftier
 */
function ph_eject_team_success(message, team_id) {
    ph_delete_team_row(message, team_id);

    // On reload l'arbre, les équipes et les équipes bloquées, car elles ont été modifiées
    $('#tournament-tree').load(window.location.href + ' #tournament-tree>*');
    $('#teams').load(window.location.href + ' #teams>*');
    $('#blocked').load(window.location.href + ' #blocked>*');
    $('#nb-registered-teams').load(window.location.href + ' #nb-registered-teams');
}

/**
 * Fonction de callback lorsqu'une équipe a été acceptée
 * 
 * @author Benoit Huftier
 */
function ph_accept_team_success(message, team_id) {
    ph_delete_team_row(message, team_id);

    // On reload l'arbre et les équipes car elles ont été modifiées
    $('#tournament-tree').load(window.location.href + ' #tournament-tree>*');
    $('#teams').load(window.location.href + ' #teams>*');
    $('#actual').load(window.location.href + ' #actual>*');
    $('#nb-registered-teams').load(window.location.href + ' #nb-registered-teams');
}

/**
 * Fonction de callback lorsqu'une équipe a été refusée
 * 
 * @author Benoit Huftier
 */
function ph_refuse_team_success(message, team_id) {
    ph_delete_team_row(message, team_id);

    // On reload les équipes bloquées car elles ont été modifiées
    $('#blocked').load(window.location.href + ' #blocked>*');
}

/**
 * Fonction qui gère les éjections d'équipes du tournoi par le manager.
 * 
 * @author Benoît Huftier
 */
function ph_eject_team(team_id) {
    ph_send_preinscription_validation('remove-team', team_id, ph_eject_team_success, ph_display_postulate_error);
}

/**
 * Fonction qui gère les acceptations d'équipes du tournoi par le manager.
 * 
 * @author Benoît Huftier
 */
function ph_accept_team(team_id) {
    ph_send_preinscription_validation('accept-team', team_id, ph_accept_team_success, ph_display_postulate_error);
}

/**
 * Fonction qui gère les refus d'équipes du tournoi par le manager.
 * 
 * @author Benoît Huftier
 */
function ph_refuse_team(team_id) {
    ph_send_preinscription_validation('refuse-team', team_id, ph_refuse_team_success, ph_display_postulate_error);
}

/**
 * Fonction qui ajoute une équipe bloquée à un tournoi.
 * 
 * @author Benoît Huftier
 */
function ph_accept_refused_team(team_id) {
    ph_send_preinscription_validation('accept-refused-team', team_id, ph_accept_team_success, ph_display_postulate_error);
}

/**
 * Fonction qui donne la possibilité à une équipe bloquée de repostuler.
 * 
 * @author Benoît Huftier
 */
function ph_unblock_team(team_id) {
    ph_send_preinscription_validation('unblock-team', team_id, ph_delete_team_row, ph_display_postulate_error);
}
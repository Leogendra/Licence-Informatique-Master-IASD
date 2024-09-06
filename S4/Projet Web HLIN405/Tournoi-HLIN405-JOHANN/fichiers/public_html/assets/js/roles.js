
/**
 * Récupère les données du formulaire associé au bouton, les envoient dans la 
 * validation pour modifier les rôles, et recharge le modal associé.
 * 
 * @param {DOMElement} button Le bouton de soumission.
 */
function ph_save_roles(button) {
    let form = button.form;

    if ('undefined' === typeof form) {
        console.error('Le bouton de soumission des rôles n\'est pas dans un formulaire.');
    }

    let formData = new FormData(form);

    const getCheckboxData = (role) => {
        return formData.has(role);
    };

    const datas = {
        user_id: formData.get('user-id'),
        role_admin: getCheckboxData('role-1'),
        role_manager: getCheckboxData('role-2'),
        role_player: getCheckboxData('role-4')
    };

    const reload = () => {
        $('#popup-' + datas.user_id).modal('hide');
        $('#table').load(window.location.href + ' #table');
    }

    const createAlert = (message, cls, parentId) => {
        document.querySelector('#' + parentId).innerHTML = 
            '<div class="alert alert-' + cls + ' alert-dismissible fade show" role="alert">' + message + 
            ' <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
    }

    const deleteAlerts = (type) => {
        let alerts = $('.alert-' + type);
        if (null !== alerts) {
            Array.from(alerts).forEach((al) => {
                $(al).alert('close');
            });
        }
    }

    const onSuccess = (message) => {
        reload();
        deleteAlerts('danger');
        createAlert(message, 'success', 'success-message');
    };

    const onFail = (message) => {
        reload();
        deleteAlerts('success');
        createAlert(message, 'danger', 'fail-message');
    }

    ph_send_validation('validation/modify-roles.php', datas, onSuccess, onFail);
}
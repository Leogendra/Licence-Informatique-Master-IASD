if (null !== document.querySelector('#reset-preinscriptions-modal .is-invalid, #reset-preinscriptions-modal .is-valid')) {
    let myModal = new bootstrap.Modal(document.getElementById('reset-preinscriptions-modal'));
    myModal.show();
}

if (null !== document.querySelector('#update-tournament .is-invalid, #update-tournament .is-valid')) {
    let myModal = new bootstrap.Modal(document.getElementById('update-tournament-form-modal'));
    myModal.show();
}
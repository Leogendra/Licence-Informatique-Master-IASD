if (null !== document.querySelector('#toggle-update-modal .is-invalid, #toggle-update-modal .is-valid')) {
    let myModal = new bootstrap.Modal(document.getElementById('toggle-update-modal'));
    myModal.show();
}
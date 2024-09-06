if (null !== document.querySelector('#updateInfos .is-invalid, #updateInfos .is-valid')) {
    let myModal = new bootstrap.Modal(document.getElementById('updateInfos'));
    myModal.show();
}
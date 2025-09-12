// import '@modules/UserModule/resources/assets/js/app.js';


window.success = function (title, message) {
    Swal.fire({
        title: title,
        text: message,
        icon: 'success',
        showConfirmButton: false,
        showCancelButton: false,
    })

}

window.danger = function (title, message) {
    Swal.fire({
        title: title,
        text: message,
        icon: 'error',
        showConfirmButton: false,
        showCancelButton: false,
    })

}
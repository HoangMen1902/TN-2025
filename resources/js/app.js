// import '@modules/UserModule/resources/assets/js/app.js';


    window.success = function (title, message) {
        Swal.fire({
            title: title,
            text: message,
            icon: 'success',
            showConfirmButton: false,
            showCancelButton:false,
        })
        
    }
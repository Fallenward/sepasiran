import Swiper from 'swiper/bundle';
import 'swiper/css/bundle';
window.Swiper = Swiper;
import 'bootstrap';
import '@popperjs/core';
import Swal from 'sweetalert2';
window.Swal = Swal;


$(document).ready(function () {


    $('.select2').select2({
        placeholder: 'انتخاب کنید',
        width: '100%',
        dropdownCssClass: 'select2-dropdown-custom',
        dir: 'rtl',
    });

    // Select all input, select, and textarea elements
    const inputs = document.querySelectorAll('input, select, textarea');

    inputs.forEach(input => {
        // Set autocomplete to "off"


        // Add a random attribute to confuse the browser
        input.setAttribute('data-autocomplete', 'off');

        // For sensitive fields, use "new-password"
        if (input.type === 'password' || input.type === 'text') {
            input.setAttribute('autocomplete', 'new-password');
        }

    });
});
window.showMessage = function (message, type) {
    const Toast = Swal.mixin({
        toast: true,
        position: "top-end",
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.onmouseenter = Swal.stopTimer;
            toast.onmouseleave = Swal.resumeTimer;
        }
    });
    Toast.fire({
        icon: type,
        title: message
    });
};

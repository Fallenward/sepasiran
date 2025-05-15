import "@fortawesome/fontawesome-free/css/all.css";
import "bootstrap";
import "./adminlte3";
import "./bootstrap";
import Swal from 'sweetalert2';
window.Swal = Swal;

axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');


$(document).ready(function () {
    $('.select2').select2({
        placeholder: 'انتخاب کنید',
        width: '100%',
        dropdownCssClass: 'select2-dropdown-custom',
        dir: 'rtl',
        allowClear: true, // Optional: Allow clearing the selection
    });
    $('.clockpicker').clockpicker({
        placement: 'bottom',
        align: 'left',
        donetext: 'Done'
    });
    /**@type {HTMLAnchorElement} */
    const logoutAnchor = document.querySelector("#logout-button");
    if (logoutAnchor) {
        logoutAnchor.addEventListener("click", function (e) {
            e.preventDefault();

            /**@type {HTMLFormElement} */
            let logoutForm = document.querySelector("#logout-form");
            logoutForm.submit();
        });
    }

    /**@type {HTMLTableElement} */
    let defaultDataTables = document.querySelector("#datatables");
    if (defaultDataTables) {
        /**@type {string | undefined} */
        let datatablesSource = defaultDataTables.dataset.route;
        let tableConfigs = defaultDataTables.dataset.configs;

        const reloadDeleteModal = () => {
            const deleteButtons = document.querySelectorAll("button.delete-btn");

            deleteButtons.forEach((deleteButton) => {
                deleteButton.addEventListener("click", (e) => {

                    const destroyUrl = deleteButton.getAttribute("data-destroy");
                    const deleteForm = document.querySelector("div#delete-form");
                    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                    let form = `
                    <form action="${destroyUrl}" method="POST">
                        <input type="hidden" name="_method" value="DELETE">
                        <input type="hidden" name="_token" value="${csrfToken}" autocomplete="off">
                        <div class="modal-header">
                            <h4 class="modal-title">Delete Data</h4>
                            <button type="button" class="close dismiss-modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p>Delete this data? This process can not be undone.</div>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default dismiss-modal">Cancel</button>
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </div>
                    </form>
                    `;

                    deleteForm.innerHTML = form;

                    $('div#delete-modal').modal({show: true, focus: true, keyboard: false, backdrop: 'static'});

                    document.querySelectorAll('.dismiss-modal').forEach((element) => {
                        element.addEventListener("click", () => {
                            $('div#delete-modal').modal('hide');
                        });
                    });
                });
            });
        };

        new DataTable("#datatables", {
            serverSide: true,
            processing: true,
            responsive: true,
            keys: true,
            stateSave: true,
            autoWidth: false,
            info: true,
            ajax: datatablesSource,
            columns: JSON.parse(tableConfigs),
            drawCallback: function () {
                reloadDeleteModal();
            },
        });
    }


});

document.addEventListener("DOMContentLoaded", function () {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
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

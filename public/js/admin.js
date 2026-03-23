document.addEventListener("DOMContentLoaded", () => {
    const toggle = document.getElementById("sidebarToggle");
    const sidebar = document.getElementById("adminSidebar");

    if (sidebar && toggle) {
        toggle.addEventListener('click', function () {
            sidebar.classList.toggle('show');
        });
    }

    // cerrar sidebar tocando fuera (mobile)
    document.addEventListener("click", (e) => {

        if (
            window.innerWidth <= 768 &&
            sidebar.classList.contains("show") &&
            !sidebar.contains(e.target) &&
            !toggle.contains(e.target)
        ) {
            sidebar.classList.remove("show");
        }

    });

    function confirmAction({ title, text = "", confirmText = "Confirmar", confirmColor = "#212529", url }) {

        Swal.fire({
            title: title,
            text: text,
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: confirmColor,
            cancelButtonColor: "#6c757d",
            confirmButtonText: confirmText,
            cancelButtonText: "Cancelar"
        }).then((result) => {

            if (result.isConfirmed) {
                window.location.href = url;
            }

        });

    }

    document.querySelectorAll('.btn-user-action').forEach(button => {

        button.addEventListener('click', function (e) {

            e.preventDefault();

            const url = this.dataset.url;
            const action = this.dataset.action;

            let message = "";

            if (action === "activar") message = "¿Activar este usuario?";
            if (action === "desactivar") message = "¿Desactivar este usuario?";
            if (action === "admin") message = "¿Convertir usuario en ADMIN?";
            if (action === "cliente") message = "¿Convertir usuario en CLIENTE?";

            confirmAction({
                title: message,
                url: url
            });

        });

    });

    function bindDeleteConfirmation(selector, title) {
        document.querySelectorAll(selector).forEach(button => {
            button.addEventListener('click', function (e) {
                e.preventDefault();

                confirmAction({
                    title: title,
                    text: "Esta acción no se puede deshacer",
                    confirmText: "Sí, eliminar",
                    confirmColor: "#dc3545",
                    url: this.dataset.url
                });
            });
        });
    }

    bindDeleteConfirmation('.btn-delete-image', "¿Eliminar imagen?");
    bindDeleteConfirmation('.btn-delete-product', "¿Eliminar producto?");
    bindDeleteConfirmation('.btn-delete-coupon', "¿Eliminar cupón?");
    bindDeleteConfirmation('.btn-delete-category', "¿Eliminar categoría?");
    bindDeleteConfirmation('.btn-delete-promotion', "¿Eliminar promoción?");
    bindDeleteConfirmation('.btn-delete-promotion-product', "¿Eliminar producto de la promoción?");
    bindDeleteConfirmation('.btn-delete-brand', "¿Eliminar marca?");
    bindDeleteConfirmation('.btn-delete', "¿Eliminar elemento?");
});

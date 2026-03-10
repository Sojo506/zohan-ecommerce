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

        Swal.fire({

            title: message,
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#212529",
            cancelButtonColor: "#6c757d",
            confirmButtonText: "Sí, confirmar",
            cancelButtonText: "Cancelar"

        }).then((result) => {

            if (result.isConfirmed) {

                window.location.href = url;

            }

        });

    });

});

document.querySelectorAll('.btn-delete-image').forEach(button => {

    button.addEventListener('click', function (e) {

        e.preventDefault();

        const url = this.dataset.url;

        Swal.fire({
            title: '¿Eliminar imagen?',
            text: "Esta acción no se puede deshacer",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {

            if (result.isConfirmed) {
                window.location.href = url;
            }

        });

    });

});

document.querySelectorAll('.btn-delete-product').forEach(button => {

    button.addEventListener('click', function (e) {

        e.preventDefault();

        const url = this.dataset.url;

        Swal.fire({
            title: '¿Eliminar producto?',
            text: "Esta acción no se puede deshacer",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {

            if (result.isConfirmed) {
                window.location.href = url;
            }

        });

    });

});
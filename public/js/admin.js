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

document.querySelectorAll('.btn-delete-image').forEach(button => {

    button.addEventListener('click', function (e) {

        e.preventDefault();

        confirmAction({
            title: "¿Eliminar imagen?",
            text: "Esta acción no se puede deshacer",
            confirmText: "Sí, eliminar",
            confirmColor: "#dc3545",
            url: this.dataset.url
        });

    });

});

document.querySelectorAll('.btn-delete-product').forEach(button => {

    button.addEventListener('click', function (e) {

        e.preventDefault();

        confirmAction({
            title: "¿Eliminar producto?",
            text: "Esta acción no se puede deshacer",
            confirmText: "Sí, eliminar",
            confirmColor: "#dc3545",
            url: this.dataset.url
        });

    });

});

document.querySelectorAll('.btn-delete-coupon').forEach(button => {

    button.addEventListener('click', function (e) {

        e.preventDefault();

        confirmAction({
            title: "¿Eliminar cupón?",
            text: "Esta acción no se puede deshacer",
            confirmText: "Sí, eliminar",
            confirmColor: "#dc3545",
            url: this.dataset.url
        });

    });

});

document.querySelectorAll('.btn-delete-category').forEach(button => {

    button.addEventListener('click', function (e) {
        console.log("Eliminar categoría");
        e.preventDefault();

        confirmAction({
            title: "¿Eliminar categoría?",
            text: "Esta acción no se puede deshacer",
            confirmText: "Sí, eliminar",
            confirmColor: "#dc3545",
            url: this.dataset.url
        });

    });

});


document.querySelectorAll('.btn-delete-promotion').forEach(button => {

    button.addEventListener('click', function (e) {
        console.log("Eliminar promoción");
        e.preventDefault();

        confirmAction({
            title: "¿Eliminar promoción?",
            text: "Esta acción no se puede deshacer",
            confirmText: "Sí, eliminar",
            confirmColor: "#dc3545",
            url: this.dataset.url
        });

    });

});

document.querySelectorAll('.btn-delete-promotion-product').forEach(button => {

    button.addEventListener('click', function (e) {
        console.log("Eliminar producto de la promoción");
        e.preventDefault();

        confirmAction({
            title: "¿Eliminar producto de la promoción?",
            text: "Esta acción no se puede deshacer",
            confirmText: "Sí, eliminar",
            confirmColor: "#dc3545",
            url: this.dataset.url
        });

    });

});
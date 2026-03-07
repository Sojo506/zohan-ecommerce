document.addEventListener("DOMContentLoaded", () => {
    const sidebar = document.getElementById('adminSidebar');
    const toggleButton = document.getElementById('sidebarToggle');

    if (sidebar && toggleButton) {
        toggleButton.addEventListener('click', function () {
            sidebar.classList.toggle('show');
        });
    }

    const pass1 = document.getElementById("pass1");
    const pass2 = document.getElementById("pass2");
    const showPass = document.getElementById("showPass");
    const passMatch = document.getElementById("passMatch");

    function updateMatch() {
        if (!pass1 || !pass2 || !passMatch) return;

        if (!pass2.value) {
            passMatch.textContent = "";
            passMatch.className = "small text-muted";
            return;
        }

        if (pass1.value === pass2.value) {
            passMatch.textContent = "Las contraseñas coinciden";
            passMatch.className = "small text-success";
        } else {
            passMatch.textContent = "Las contraseñas no coinciden";
            passMatch.className = "small text-danger";
        }
    }

    if (pass1) pass1.addEventListener("input", updateMatch);
    if (pass2) pass2.addEventListener("input", updateMatch);

    if (showPass) {
        showPass.addEventListener("change", () => {
            const type = showPass.checked ? "text" : "password";

            if (pass1) pass1.setAttribute("type", type);
            if (pass2) pass2.setAttribute("type", type);
        });
    }

    const forms = document.querySelectorAll(".needs-validation");

    Array.from(forms).forEach((form) => {
        form.addEventListener("submit", (event) => {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }

            form.classList.add("was-validated");
        });
    });
});

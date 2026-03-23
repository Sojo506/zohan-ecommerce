document.addEventListener("DOMContentLoaded", () => {
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

    const reviewToggles = document.querySelectorAll(".rating-toggle");
    reviewToggles.forEach((link) => {
        link.addEventListener("click", (event) => {
            const targetId = link.getAttribute("href")?.replace("#", "");
            if (!targetId) return;

            const target = document.getElementById(targetId);
            if (!target) return;

            event.preventDefault();
            target.scrollIntoView({ behavior: "smooth", block: "start" });

            if (target.focus) {
                target.focus({ preventScroll: true });
            }
        });
    });

    const reviewSections = document.querySelectorAll(".rating-reviews");
    reviewSections.forEach((section) => {
        const chips = Array.from(section.querySelectorAll(".review-chip"));
        const reviews = Array.from(section.querySelectorAll(".rating-review"));
        const mediaItems = Array.from(section.querySelectorAll(".reviews-media-item"));

        if (chips.length > 0) {
            const applyFilter = (filter) => {
                reviews.forEach((item) => {
                    const tags = (item.dataset.reviewTags || "").split(/\s+/).filter(Boolean);
                    const hasPhoto = item.dataset.reviewHasPhoto === "1";
                    let visible = true;

                    if (filter === "photos") {
                        visible = hasPhoto;
                    } else if (filter === "all") {
                        visible = true;
                    } else {
                        visible = tags.includes(filter);
                    }

                    item.style.display = visible ? "" : "none";
                });

                mediaItems.forEach((item) => {
                    if (filter === "photos" || filter === "all") {
                        item.style.display = "";
                    } else {
                        item.style.display = "none";
                    }
                });
            };

            chips.forEach((chip) => {
                chip.addEventListener("click", () => {
                    const filter = chip.dataset.reviewFilter || "all";

                    chips.forEach((btn) => btn.classList.remove("is-active"));
                    chip.classList.add("is-active");

                    applyFilter(filter);
                    section.scrollIntoView({ behavior: "smooth", block: "start" });
                });
            });
        }
    });

    const mainProductImage = document.getElementById("productMainImage");
    const productThumbs = document.querySelectorAll("[data-product-thumb]");

    if (mainProductImage && productThumbs.length > 0) {
        productThumbs.forEach((thumb) => {
            thumb.addEventListener("click", () => {
                const nextImage = thumb.getAttribute("data-product-thumb");

                if (!nextImage) return;

                mainProductImage.setAttribute("src", nextImage);

                productThumbs.forEach((item) => item.classList.remove("is-active"));
                thumb.classList.add("is-active");
            });
        });
    }
});
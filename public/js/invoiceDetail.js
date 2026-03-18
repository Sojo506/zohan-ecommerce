document.addEventListener('DOMContentLoaded', () => {
    const main = document.querySelector('main[data-invoice-id][data-comment-url]');
    if (!main || main.dataset.commentsBound === '1') {
        return;
    }
    main.dataset.commentsBound = '1';

    const invoiceId = main.dataset.invoiceId;
    const commentUrl = main.dataset.commentUrl;
    const ratingLabels = {
        1: 'Muy malo',
        2: 'Malo',
        3: 'Regular',
        4: 'Bueno',
        5: 'Excelente'
    };
    const sending = new Set();

    document.addEventListener('click', (e) => {
        const btn = e.target.closest('.js-toggle-comment');
        if (!btn) return;

        const productId = btn.dataset.productId;
        const box = document.querySelector(`[data-comment-box="${productId}"]`);
        if (!box) return;

        if (!btn.dataset.defaultText) {
            btn.dataset.defaultText = btn.textContent.trim();
        }

        box.classList.toggle('d-none');
        const isHidden = box.classList.contains('d-none');
        btn.textContent = isHidden ? btn.dataset.defaultText : 'Ocultar';
    });

    document.querySelectorAll('.rating-pill').forEach((pill) => {
        pill.addEventListener('click', () => {
            const box = pill.closest('.comment-box');
            if (!box) return;

            box.querySelectorAll('.rating-pill').forEach((b) => {
                b.classList.remove('is-active');
            });

            pill.classList.add('is-active');

            const value = parseInt(pill.dataset.rating || '5', 10);
            const input = box.querySelector('.js-rating-value');
            const label = box.querySelector('.js-rating-label');

            if (input) input.value = value;
            if (label) label.textContent = `${value} - ${ratingLabels[value] || ''}`;
        });
    });

    document.querySelectorAll('.js-send-comment').forEach((btn) => {
        btn.addEventListener('click', () => {
            const productId = btn.dataset.productId;
            if (sending.has(productId)) {
                return;
            }

            const box = document.querySelector(`[data-comment-box="${productId}"]`);
            if (!box) return;

            const textarea = box.querySelector('.js-comment-text');
            const status = box.querySelector('.js-comment-status');
            const ratingValue = box.querySelector('.js-rating-value');

            const comment = textarea ? textarea.value.trim() : '';
            const rating = ratingValue ? parseInt(ratingValue.value || '5', 10) : 5;

            if (!comment) {
                if (status) {
                    status.textContent = 'Escribe un comentario antes de enviar.';
                    status.className = 'small mt-2 text-danger js-comment-status';
                    status.classList.remove('d-none');
                }
                return;
            }

            sending.add(productId);
            btn.disabled = true;
            if (status) {
                status.className = 'small mt-2 text-muted js-comment-status';
                status.classList.remove('d-none');
            }

            fetch(commentUrl, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    invoice_id: invoiceId,
                    product_id: productId,
                    comment: comment,
                    rating: rating
                })
            })
                .then((response) => response.json())
                .then((data) => {
                    if (data.success) {
                        if (textarea) textarea.value = '';
                        if (box) box.classList.add('d-none');
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Comentario enviado',
                                text: data.message || 'Tu comentario se guardó correctamente.',
                                confirmButtonText: 'Entendido'
                            });
                        }
                    } else if (status) {
                        status.textContent = data.message || 'No se pudo enviar el comentario.';
                        status.className = 'small mt-2 text-danger js-comment-status';
                        status.classList.remove('d-none');
                    }
                })
                .catch(() => {
                    if (status) {
                        status.textContent = 'Error inesperado al enviar el comentario.';
                        status.className = 'small mt-2 text-danger js-comment-status';
                        status.classList.remove('d-none');
                    }
                })
                .finally(() => {
                    sending.delete(productId);
                    btn.disabled = false;
                });
        });
    });
});


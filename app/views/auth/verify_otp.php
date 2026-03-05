<h2 class="mb-3">Verificar cuenta</h2>

<?php if (!empty($_SESSION['flash_error'])): ?>
    <div class="alert alert-danger">
        <?= htmlspecialchars($_SESSION['flash_error']) ?>
    </div>
    <?php unset($_SESSION['flash_error']); ?>
<?php endif; ?>

<?php if (!empty($_SESSION['DEV_LAST_OTP'])): ?>
    <div class="alert alert-warning">
        <b>DEV:</b> OTP enviado a <?= htmlspecialchars($_SESSION['DEV_LAST_OTP_EMAIL'] ?? '') ?>:
        <b><?= htmlspecialchars($_SESSION['DEV_LAST_OTP']) ?></b>
    </div>
<?php endif; ?>

<form method="POST" action="<?= App::url('/verify-otp') ?>" class="card p-3">
    <div class="mb-3">
        <label class="form-label">Código de verificación (6 dígitos)</label>
        <input type="text" name="otp" class="form-control" maxlength="6" required>
    </div>

    <button class="btn btn-success w-100">Verificar</button>
</form>
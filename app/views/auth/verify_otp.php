<?php $otpFlow = $otpFlow ?? null; ?>

<h2 class="mb-3">
    <?= $otpFlow === 'guest' || $otpFlow === 'profile' ? 'Verificar código' : 'Verificar cuenta' ?>
</h2>

<?php if (!empty($_SESSION['flash_success'])): ?>
    <div class="alert alert-success">
        <?= htmlspecialchars($_SESSION['flash_success']) ?>
    </div>
    <?php unset($_SESSION['flash_success']); ?>
<?php endif; ?>

<?php if (!empty($_SESSION['flash_error'])): ?>
    <div class="alert alert-danger">
        <?= htmlspecialchars($_SESSION['flash_error']) ?>
    </div>
    <?php unset($_SESSION['flash_error']); ?>
<?php endif; ?>

<form method="POST" action="<?= App::url('/verify-otp') ?>" class="card p-3">
    <div class="mb-3">
        <label class="form-label">Código de verificación (6 dígitos)</label>
        <input type="text" name="otp" class="form-control" maxlength="6" required>
    </div>

    <button class="btn btn-success w-100">Verificar</button>
</form>

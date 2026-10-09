<?= view('layouts/header', ['pageTitle' => 'Reset Password']) ?>
<div class="card">
    <h1>Reset Password</h1>
    <p class="muted">Enter the account username and a new password.</p>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="notice error"><?= esc(session()->getFlashdata('error')) ?></div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('errors')): ?>
        <div class="notice error">
            <ul>
                <?php foreach (session()->getFlashdata('errors') as $error): ?>
                    <li><?= esc($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="post" action="<?= site_url('reset-password') ?>">
        <?= csrf_field() ?>

        <label for="username">Username</label>
        <input type="text" name="username" id="username" value="<?= esc(old('username')) ?>" required>

        <label for="password">New Password</label>
        <input type="password" name="password" id="password" minlength="8" required>

        <label for="password_confirm">Confirm New Password</label>
        <input type="password" name="password_confirm" id="password_confirm" minlength="8" required>

        <p><button type="submit">Reset Password</button></p>
    </form>

    <p><a href="<?= site_url('login') ?>">← Back to login</a></p>
</div>
<?= view('layouts/footer') ?>

<?= view('layouts/header', ['pageTitle' => 'Login']) ?>
<div class="card">

<h1>Account Login</h1>
<p class="muted">Use your account credentials. The system will automatically identify whether you are an admin or staff member after login.</p>

<?php if (session()->getFlashdata('error')): ?>
    <p style="color: red;">
        <?= esc(session()->getFlashdata('error')) ?>
    </p>
<?php endif; ?>

<?php if (session()->getFlashdata('success')): ?>
    <p style="color: green;">
        <?= esc(session()->getFlashdata('success')) ?>
    </p>
<?php endif; ?>

<?php if (session()->getFlashdata('errors')): ?>
    <ul style="color: red;">
        <?php foreach (session()->getFlashdata('errors') as $error): ?>
            <li><?= esc($error) ?></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<form method="post" action="<?= site_url('login') ?>">
    <?= csrf_field() ?>

    <label for="username">Username</label>
    <input
        type="text"
        name="username"
        id="username"
        value="<?= old('username') ?>"
        required
    >

    <br><br>

    <label for="password">Password</label>
    <input
        type="password"
        name="password"
        id="password"
        required
    >

    <br><br>

    <button type="submit">Login</button>
</form>

</div>
<?= view('layouts/footer') ?>

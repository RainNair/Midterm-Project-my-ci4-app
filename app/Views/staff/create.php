<?= view('layouts/header', ['pageTitle' => 'Add Staff']) ?>
<div class="card">

<h1>Add Staff Account</h1>

<p><a href="<?= site_url('staff') ?>">← Back to Staff</a></p>

<?php if (session()->getFlashdata('errors')): ?>
    <ul style="color: red;">
        <?php foreach (session()->getFlashdata('errors') as $error): ?>
            <li><?= esc($error) ?></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<form
    method="post"
    action="<?= site_url('staff/create') ?>"
    enctype="multipart/form-data"
>
    <?= csrf_field() ?>

    <label>Username</label><br>
    <input
        type="text"
        name="username"
        value="<?= old('username') ?>"
        required
    >

    <br><br>

    <label>Full Name</label><br>
    <input
        type="text"
        name="full_name"
        value="<?= old('full_name') ?>"
        required
    >

    <br><br>

    <label>Password</label><br>
    <input type="password" name="password" required>

    <label>Role</label>
    <select name="role" required>
        <option value="staff" <?= old('role', 'staff') === 'staff' ? 'selected' : '' ?>>Staff</option>
        <option value="admin" <?= old('role') === 'admin' ? 'selected' : '' ?>>Admin</option>
    </select>

    <br><br>

    <label>Avatar</label><br>
    <input
        type="file"
        name="avatar"
        accept="image/png,image/jpeg"
    >

    <br><br>

    <button type="submit">Create Staff</button>
</form>

</div>
<?= view('layouts/footer') ?>

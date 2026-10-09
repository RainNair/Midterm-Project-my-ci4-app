<?= view('layouts/header', ['pageTitle' => 'Edit Staff']) ?>
<div class="card">

<h1>Edit Staff Account</h1>

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
    action="<?= site_url('staff/update/' . $user['id']) ?>"
    enctype="multipart/form-data"
>
    <?= csrf_field() ?>

    <p>
        Username:
        <strong><?= esc($user['username']) ?></strong>
    </p>

    <label>Full Name</label><br>
    <input
        type="text"
        name="full_name"
        value="<?= old('full_name', $user['full_name']) ?>"
        required
    >

    <br><br>

    <label>New Password</label><br>
    <input
        type="password"
        name="password"
        placeholder="Leave blank to keep current password"
    >

    <label>Role</label>
    <select name="role" required>
        <option value="staff" <?= old('role', $user['role']) === 'staff' ? 'selected' : '' ?>>Staff</option>
        <option value="admin" <?= old('role', $user['role']) === 'admin' ? 'selected' : '' ?>>Admin</option>
    </select>

    <br><br>

    <label>New Avatar</label><br>
    <input
        type="file"
        name="avatar"
        accept="image/png,image/jpeg"
    >
    <label><input type="checkbox" name="delete_avatar" value="1" style="width:auto"> Delete current avatar</label>

    <br><br>

    <button type="submit">Update Staff</button>
</form>

</div>
<?= view('layouts/footer') ?>

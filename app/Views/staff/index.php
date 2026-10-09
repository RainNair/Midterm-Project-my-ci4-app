<?= view('layouts/header', ['pageTitle' => 'Staff Management']) ?>
<div class="card">

<h1>Staff Management</h1>

<p><a class="button" href="<?= site_url('staff/create') ?>">Add Staff</a></p>

<?php if (session()->getFlashdata('success')): ?>
    <p style="color: green;">
        <?= esc(session()->getFlashdata('success')) ?>
    </p>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
    <p style="color: red;">
        <?= esc(session()->getFlashdata('error')) ?>
    </p>
<?php endif; ?>

<table border="1" cellpadding="8">
    <tr>
        <th>Avatar</th>
        <th>Username</th>
        <th>Full Name</th>
        <th>Role</th>
        <th>Actions</th>
    </tr>

    <?php foreach ($users as $user): ?>
        <tr>
            <td>
                <?php if ($user['avatar']): ?>
                    <img
                        src="<?= base_url('uploads/avatars/' . esc($user['avatar'])) ?>"
                        width="60"
                        height="60"
                        alt="Avatar"
                    >
                <?php else: ?>
                    No avatar
                <?php endif; ?>
            </td>

            <td><?= esc($user['username']) ?></td>
            <td><?= esc($user['full_name']) ?></td>
            <td><span class="role-badge"><?= esc($user['role']) ?></span></td>

            <td>
                <a href="<?= site_url('staff/edit/' . $user['id']) ?>">
                    Edit
                </a>

                <form
                    method="post"
                    action="<?= site_url('staff/delete/' . $user['id']) ?>"
                    style="display:inline"
                    onsubmit="return confirm('Delete this staff account?')"
                >
                    <?= csrf_field() ?>
                    <button type="submit">Delete</button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

</div>
<?= view('layouts/footer') ?>

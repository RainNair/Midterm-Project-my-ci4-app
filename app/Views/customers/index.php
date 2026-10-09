<?= view('layouts/header', ['pageTitle' => 'Customers']) ?>
<div class="card">

<h1>Customer Management</h1>

<p><a class="button" href="<?= site_url('customers/create') ?>">Add Customer</a></p>

<?php if (session()->getFlashdata('success')): ?>
    <p style="color: green;">
        <?= esc(session()->getFlashdata('success')) ?>
    </p>
<?php endif; ?>

<table border="1" cellpadding="8">
    <tr>
        <th>ID</th>
        <th>Full Name</th>
        <th>Email</th>
        <th>Phone</th>
        <th>Actions</th>
    </tr>

    <?php foreach ($customers as $customer): ?>
        <tr>
            <td><?= esc($customer['id']) ?></td>
            <td><?= esc($customer['full_name']) ?></td>
            <td><?= esc($customer['email']) ?></td>
            <td><?= esc($customer['phone'] ?: '—') ?></td>
            <td>
                <?php if (session()->get('role') === 'admin'): ?><a class="button" href="<?= site_url('customers/edit/' . $customer['id']) ?>">
                    Edit
                </a>

                <form
                    method="post"
                    action="<?= site_url('customers/delete/' . $customer['id']) ?>"
                    style="display: inline"
                    onsubmit="return confirm('Delete this customer?')"
                >
                    <?= csrf_field() ?>
                    <button class="danger" type="submit">Delete</button>
                </form>
                <?php endif; ?>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

</div>
<?= view('layouts/footer') ?>

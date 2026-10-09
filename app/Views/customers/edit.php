<?= view('layouts/header', ['pageTitle' => 'Edit Customer']) ?>
<div class="card">

<h1>Edit Customer</h1>

<p><a href="<?= site_url('customers') ?>">← Back to Customers</a></p>

<?php if (session()->getFlashdata('errors')): ?>
    <ul style="color: red;">
        <?php foreach (session()->getFlashdata('errors') as $error): ?>
            <li><?= esc($error) ?></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<form
    method="post"
    action="<?= site_url('customers/update/' . $customer['id']) ?>"
>
    <?= csrf_field() ?>

    <label>Full Name</label><br>
    <input
        type="text"
        name="full_name"
        value="<?= old('full_name', $customer['full_name']) ?>"
        required
    >

    <br><br>

    <label>Email</label><br>
    <input
        type="email"
        name="email"
        value="<?= old('email', $customer['email']) ?>"
        required
    >

    <br><br>

    <label>Phone</label><br>
    <input
        type="text"
        name="phone"
        value="<?= old('phone', $customer['phone']) ?>"
    >

    <br><br>

    <button type="submit">Update Customer</button>
</form>

</div>
<?= view('layouts/footer') ?>

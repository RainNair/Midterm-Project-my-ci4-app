<!DOCTYPE html>
<html>
<head>
    <title>Add Customer</title>
</head>
<body>

<h1>Add Customer</h1>

<a href="<?= site_url('customers') ?>">Back to Customers</a>

<?php if (session()->getFlashdata('errors')): ?>
    <ul style="color: red;">
        <?php foreach (session()->getFlashdata('errors') as $error): ?>
            <li><?= esc($error) ?></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<form method="post" action="<?= site_url('customers/create') ?>">
    <?= csrf_field() ?>

    <label>Full Name</label><br>
    <input
        type="text"
        name="full_name"
        value="<?= old('full_name') ?>"
        required
    >

    <br><br>

    <label>Email</label><br>
    <input
        type="email"
        name="email"
        value="<?= old('email') ?>"
        required
    >

    <br><br>

    <label>Phone</label><br>
    <input
        type="text"
        name="phone"
        value="<?= old('phone') ?>"
    >

    <br><br>

    <button type="submit">Save Customer</button>
</form>

</body>
</html>
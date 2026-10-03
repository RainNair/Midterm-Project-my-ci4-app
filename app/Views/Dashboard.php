<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
</head>
<body>

    <h1>Admin Dashboard</h1>

    <p>
        Welcome,
        <?= esc(session()->get('full_name')) ?>!
    </p>

    <nav>
        <a href="<?= site_url('dashboard') ?>">Dashboard</a> |
        <a href="<?= site_url('products') ?>">Products</a> |
        <a href="<?= site_url('customers') ?>">Customers</a> |
        <a href="<?= site_url('sales') ?>">Sales History</a> |
        <a href="<?= site_url('staff') ?>">Staff</a> |
        <a href="<?= site_url('logout') ?>">Logout</a>
    </nav>

    <hr>

    <h2>Welcome to the Admin Panel</h2>
    <p>Use the navigation links above to manage the system.</p>

</body>
</html>
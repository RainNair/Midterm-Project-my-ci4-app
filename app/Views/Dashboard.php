<?= view('layouts/header', ['pageTitle' => 'Dashboard']) ?>
<div class="card">

    <h1><?= $isAdmin ? 'Admin Dashboard' : 'Staff Dashboard' ?></h1>

    <p>
        Welcome,
        <?= esc(session()->get('full_name')) ?>!
    </p>

    <h2>Welcome to the POS Panel</h2>
    <p>Your account was identified as <strong><?= esc(ucfirst($role)) ?></strong>. The navigation and server permissions are based on this role.</p>

    <?php if ($isAdmin): ?>
        <p>As an admin, you can manage products, customers, staff accounts, and sales.</p>
    <?php else: ?>
        <p>As staff, you can view products and customers, record sales, and review sales history.</p>
    <?php endif; ?>

</div>
<?= view('layouts/footer') ?>

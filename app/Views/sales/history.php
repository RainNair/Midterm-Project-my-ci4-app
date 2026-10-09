<?= view('layouts/header', ['pageTitle' => 'Sales History']) ?>
<div class="card">

<h1>Sales History</h1>

<p><a class="button" href="<?= site_url('sales/create') ?>">Record Sale</a></p>

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
        <th>ID</th>
        <th>Product</th>
        <th>Customer</th>
        <th>Staff</th>
        <th>Quantity</th>
        <th>Total Price</th>
        <th>Date</th>
    </tr>

    <?php foreach ($sales as $sale): ?>
        <tr>
            <td><?= esc($sale['id']) ?></td>
            <td><?= esc($sale['product_name']) ?></td>
            <td>
                <?= esc($sale['customer_name'] ?: 'Walk-in Customer') ?>
            </td>
            <td><?= esc($sale['staff_name']) ?></td>
            <td><?= esc($sale['quantity']) ?></td>
            <td>₱<?= number_format($sale['total_price'], 2) ?></td>
            <td><?= esc($sale['created_at']) ?></td>
        </tr>
    <?php endforeach; ?>
</table>

</div>
<?= view('layouts/footer') ?>

<?= view('layouts/header', ['pageTitle' => 'Sales Receipt']) ?>
<div class="card">
    <h1>Sales Receipt</h1>
    <p><strong>Receipt No.:</strong> <?= esc($receiptNumber) ?></p>
    <p><strong>Customer:</strong> <?= esc($sales[0]['customer_name']) ?></p>
    <p><strong>Staff:</strong> <?= esc($sales[0]['staff_name']) ?></p>
    <table>
        <tr><th>Product</th><th>Quantity</th><th>Price</th><th>Total</th></tr>
        <?php $grandTotal = 0; foreach ($sales as $sale): $grandTotal += (float) $sale['total_price']; ?>
            <tr>
                <td><?= esc($sale['product_name']) ?></td>
                <td><?= esc($sale['quantity']) ?></td>
                <td>₱<?= number_format($sale['total_price'] / $sale['quantity'], 2) ?></td>
                <td>₱<?= number_format($sale['total_price'], 2) ?></td>
            </tr>
        <?php endforeach; ?>
        <tr><th colspan="3">Grand Total</th><th>₱<?= number_format($grandTotal, 2) ?></th></tr>
    </table>
    <p>
        <a class="button" href="<?= site_url('sales/edit/' . $sales[0]['id']) ?>">Edit Receipt / Add Products</a>
        <a class="button" href="<?= site_url('sales/history') ?>">Back to Sales History</a>
    </p>
</div>
<?= view('layouts/footer') ?>

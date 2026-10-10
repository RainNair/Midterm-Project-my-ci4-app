<?= view('layouts/header', ['pageTitle' => 'Record Sale']) ?>
<div class="card">
    <h1>Record Sale</h1>

    <?php if (session()->getFlashdata('errors')): ?>
        <div class="notice error"><ul><?php foreach (session()->getFlashdata('errors') as $error): ?><li><?= esc($error) ?></li><?php endforeach; ?></ul></div>
    <?php endif; ?>

    <form method="post" action="<?= site_url('sales/store') ?>">
        <?= csrf_field() ?>
        <label for="customer_id">Customer</label>
        <select name="customer_id" id="customer_id" required>
            <option value="">Select Customer</option>
            <?php foreach ($customers as $customer): ?>
                <option value="<?= esc($customer['id']) ?>" <?= old('customer_id') == $customer['id'] ? 'selected' : '' ?>><?= esc($customer['full_name']) ?></option>
            <?php endforeach; ?>
        </select>

        <h2>Items</h2>
        <div id="items">
            <div class="sale-item" style="display:flex;gap:10px;align-items:end;margin-bottom:12px;flex-wrap:wrap">
                <div>
                    <label>Product</label>
                    <select name="product_id[]" required>
                        <option value="">Select Product</option>
                        <?php foreach ($products as $product): ?>
                            <option value="<?= esc($product['id']) ?>"><?= esc($product['name']) ?> - ₱<?= number_format($product['price'], 2) ?> (Stock: <?= esc($product['stock_quantity']) ?>)</option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label>Quantity</label>
                    <input type="number" name="quantity[]" min="1" value="1" required>
                </div>
                <button type="button" class="danger remove-item">Remove</button>
            </div>
        </div>

        <button type="button" id="add-item">Add Another Item</button>
        <p><button type="submit">Record Sale and View Receipt</button></p>
    </form>
</div>
<script>
document.getElementById('add-item').addEventListener('click', function () {
    const first = document.querySelector('.sale-item');
    const copy = first.cloneNode(true);
    copy.querySelector('select').selectedIndex = 0;
    copy.querySelector('input').value = 1;
    document.getElementById('items').appendChild(copy);
});
document.addEventListener('click', function (event) {
    if (event.target.classList.contains('remove-item')) {
        const rows = document.querySelectorAll('.sale-item');
        if (rows.length > 1) event.target.closest('.sale-item').remove();
    }
});
</script>
<?= view('layouts/footer') ?>

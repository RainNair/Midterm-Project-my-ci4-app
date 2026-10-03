<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
</head>
<body>

<h1>Add Product</h1>

<a href="<?= site_url('products') ?>">Back to Products</a>

<hr>

<?php if (session()->getFlashdata('errors')): ?>
    <ul style="color: red;">
        <?php foreach (session()->getFlashdata('errors') as $error): ?>
            <li><?= esc($error) ?></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<form
    action="<?= site_url('products/create') ?>"
    method="post"
    enctype="multipart/form-data"
>
    <?= csrf_field() ?>

    <label for="name">Product Name</label><br>
    <input
        type="text"
        id="name"
        name="name"
        value="<?= esc(old('name')) ?>"
        required
    >

    <br><br>

    <label for="price">Price</label><br>
    <input
        type="number"
        id="price"
        name="price"
        step="0.01"
        min="0"
        value="<?= esc(old('price')) ?>"
        required
    >

    <br><br>

    <label for="stock_quantity">Stock Quantity</label><br>
    <input
        type="number"
        id="stock_quantity"
        name="stock_quantity"
        min="0"
        value="<?= esc(old('stock_quantity')) ?>"
        required
    >

    <br><br>

    <label for="image">Product Image</label><br>
    <input
        type="file"
        id="image"
        name="image"
        accept=".jpg,.jpeg,.png"
    >

    <br><br>

    <button type="submit">Save Product</button>
</form>

</body>
</html>
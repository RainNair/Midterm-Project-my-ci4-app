<?= view('layouts/header', ['pageTitle' => 'Edit Product']) ?>
<div class="card">

<h1>Edit Product</h1>

<p><a href="<?= site_url('products') ?>">← Back to Products</a></p>

<?php if (session()->getFlashdata('errors')): ?>
    <ul style="color: red;">
        <?php foreach (session()->getFlashdata('errors') as $error): ?>
            <li><?= esc($error) ?></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<form
    action="<?= site_url('products/update/' . $product['id']) ?>"
    method="post"
    enctype="multipart/form-data"
>
    <?= csrf_field() ?>

    <label for="name">Product Name</label><br>
    <input
        type="text"
        id="name"
        name="name"
        value="<?= esc(old('name', $product['name'])) ?>"
        required
    >
    <label><input type="checkbox" name="delete_image" value="1" style="width:auto"> Delete current image</label>

    <br><br>

    <label for="price">Price</label><br>
    <input
        type="number"
        id="price"
        name="price"
        step="0.01"
        min="0"
        value="<?= esc(old('price', $product['price'])) ?>"
        required
    >

    <br><br>

    <label for="stock_quantity">Stock Quantity</label><br>
    <input
        type="number"
        id="stock_quantity"
        name="stock_quantity"
        min="0"
        value="<?= esc(old('stock_quantity', $product['stock_quantity'])) ?>"
        required
    >

    <br><br>

    <?php if (! empty($product['image'])): ?>
        <p>Current image:</p>
        <img
            src="<?= base_url('uploads/products/' . $product['image']) ?>"
            width="120"
            alt="Current product image"
        >
        <br><br>
    <?php endif; ?>

    <label for="image">Replace Image</label><br>
    <input
        type="file"
        id="image"
        name="image"
        accept=".jpg,.jpeg,.png"
    >

    <br><br>

    <button type="submit">Update Product</button>
</form>

</div>
<?= view('layouts/footer') ?>

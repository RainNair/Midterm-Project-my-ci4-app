<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Products</title>
</head>
<body>

<h1>Products</h1>

<a href="<?= site_url('dashboard') ?>">Dashboard</a> |
<a href="<?= site_url('products/create') ?>">Add Product</a> |
<a href="<?= site_url('logout') ?>">Logout</a>

<hr>

<?php if (session()->getFlashdata('success')): ?>
    <p style="color: green;">
        <?= esc(session()->getFlashdata('success')) ?>
    </p>
<?php endif; ?>

<table border="1" cellpadding="8">
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Price</th>
        <th>Stock</th>
        <th>Image</th>
        <th>Actions</th>
    </tr>

    <?php foreach ($products as $product): ?>
        <tr>
            <td><?= esc($product['id']) ?></td>
            <td><?= esc($product['name']) ?></td>
            <td>₱<?= number_format((float) $product['price'], 2) ?></td>
            <td><?= esc($product['stock_quantity']) ?></td>
            <td>
                <?php if (! empty($product['image'])): ?>
                    <img
                        src="<?= base_url('uploads/products/' . $product['image']) ?>"
                        width="80"
                        alt="Product image"
                    >
                <?php else: ?>
                    No image
                <?php endif; ?>
            </td>
            <td>
                <a href="<?= site_url('products/edit/' . $product['id']) ?>">
                    Edit
                </a>

                <form
                    action="<?= site_url('products/delete/' . $product['id']) ?>"
                    method="post"
                    style="display: inline;"
                >
                    <?= csrf_field() ?>
                    <button type="submit"
                            onclick="return confirm('Delete this product?')">
                        Delete
                    </button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

</body>
</html>
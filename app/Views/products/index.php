<?= view('layouts/header', ['pageTitle' => 'Products']) ?>
<div class="card">

<h1>Products</h1>

<p><a class="button" href="<?= site_url('products/create') ?>">Add Product</a></p>

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
                <a class="button" href="<?= site_url('products/edit/' . $product['id']) ?>">Edit</a>

                <?php if (session()->get('role') === 'admin'): ?>
                <form
                    action="<?= site_url('products/delete/' . $product['id']) ?>"
                    method="post"
                    style="display: inline;"
                >
                    <?= csrf_field() ?>
                    <button class="danger" type="submit"
                            onclick="return confirm('Delete this product?')">
                        Delete
                    </button>
                </form>
                <?php endif; ?>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

</div>
<?= view('layouts/footer') ?>

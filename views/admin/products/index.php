<h1 class="text-3xl font-bold text-gray-800 dark:text-gray-100 mt-8 mb-6">Manage Products</h1>

<div class="mb-6">
    <a href="<?php echo APP_URL; ?>/admin/createProduct" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg font-medium transition">
        Add New Product
    </a>
</div>

<?php if (!empty($products)): ?>
<div class="bg-white dark:bg-gray-800 rounded-2xl shadow-md overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
        <thead class="bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 uppercase text-sm">
            <tr>
                <th class="px-4 py-3 text-left">Image</th>
                <th class="px-4 py-3 text-left">Name</th>
                <th class="px-4 py-3 text-left">Category</th>
                <th class="px-4 py-3 text-center">Price</th>
                <th class="px-4 py-3 text-center">Stock</th>
                <th class="px-4 py-3 text-center">Status</th>
                <th class="px-4 py-3 text-center">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
            <?php foreach ($products as $product): ?>
            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                <td class="px-4 py-3">
                    <?php if ($product['image']): ?>
                        <img src="<?php echo APP_URL; ?>/uploads/<?php echo $product['image']; ?>" alt="" class="w-16 h-16 object-cover rounded-md border border-gray-200 dark:border-gray-700">
                    <?php else: ?>
                        <div class="w-16 h-16 bg-gray-200 dark:bg-gray-700 rounded-md flex items-center justify-center text-gray-500">No Image</div>
                    <?php endif; ?>
                </td>
                <td class="px-4 py-3 text-gray-800 dark:text-gray-100"><?php echo htmlspecialchars($product['name']); ?></td>
                <td class="px-4 py-3 text-gray-800 dark:text-gray-100"><?php echo htmlspecialchars($product['category_name']); ?></td>
                <td class="px-4 py-3 text-center text-gray-700 dark:text-gray-300">$<?php echo number_format($product['price'], 2); ?></td>
                <td class="px-4 py-3 text-center text-gray-700 dark:text-gray-300"><?php echo $product['stock']; ?></td>
                <td class="px-4 py-3 text-center">
                    <span class="px-2 py-1 rounded text-white" style="background: <?php echo $product['is_active'] ? '#2ecc71' : '#e74c3c'; ?>">
                        <?php echo $product['is_active'] ? 'Active' : 'Inactive'; ?>
                    </span>
                </td>
                <td class="px-4 py-3 text-center flex justify-center gap-2">
                    <a href="<?php echo APP_URL; ?>/admin/editProduct/<?php echo $product['id']; ?>" class="px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition">Edit</a>
                    <a href="<?php echo APP_URL; ?>/admin/deleteProduct/<?php echo $product['id']; ?>" onclick="return confirm('Delete this product?')" class="px-3 py-1 bg-red-600 hover:bg-red-700 text-white rounded-lg transition">Delete</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php else: ?>
    <p class="text-gray-600 dark:text-gray-400 mt-4">No products found.</p>
<?php endif; ?>

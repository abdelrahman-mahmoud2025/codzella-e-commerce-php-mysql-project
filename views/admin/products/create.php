<h1 class="text-3xl font-bold text-gray-800 dark:text-gray-100 mt-8 mb-6 text-center">Create Product</h1>

<div class="max-w-3xl mx-auto my-6">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-md p-6">
        <form method="POST" action="<?php echo APP_URL; ?>/admin/createProduct" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">

            <div class="mb-4">
                <label for="name" class="block text-gray-700 dark:text-gray-200 font-medium mb-1">Product Name</label>
                <input type="text" name="name" id="name" required
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-gray-50 dark:bg-gray-900 text-gray-800 dark:text-gray-200 focus:outline-none focus:ring-emerald-500 focus:border-emerald-500">
            </div>

            <div class="mb-4">
                <label for="category_id" class="block text-gray-700 dark:text-gray-200 font-medium mb-1">Category</label>
                <select name="category_id" id="category_id" required
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-gray-50 dark:bg-gray-900 text-gray-800 dark:text-gray-200 focus:outline-none focus:ring-emerald-500 focus:border-emerald-500">
                    <option value="">Select Category</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?php echo $category['id']; ?>"><?php echo htmlspecialchars($category['name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-4">
                <label for="description" class="block text-gray-700 dark:text-gray-200 font-medium mb-1">Description</label>
                <textarea name="description" id="description" rows="5"
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-gray-50 dark:bg-gray-900 text-gray-800 dark:text-gray-200 focus:outline-none focus:ring-emerald-500 focus:border-emerald-500"></textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label for="price" class="block text-gray-700 dark:text-gray-200 font-medium mb-1">Price</label>
                    <input type="number" name="price" id="price" step="0.01" min="0" required
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-gray-50 dark:bg-gray-900 text-gray-800 dark:text-gray-200 focus:outline-none focus:ring-emerald-500 focus:border-emerald-500">
                </div>
                <div>
                    <label for="sale_price" class="block text-gray-700 dark:text-gray-200 font-medium mb-1">Sale Price (Optional)</label>
                    <input type="number" name="sale_price" id="sale_price" step="0.01" min="0"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-gray-50 dark:bg-gray-900 text-gray-800 dark:text-gray-200 focus:outline-none focus:ring-emerald-500 focus:border-emerald-500">
                </div>
            </div>

            <div class="mb-4">
                <label for="stock" class="block text-gray-700 dark:text-gray-200 font-medium mb-1">Stock Quantity</label>
                <input type="number" name="stock" id="stock" min="0" value="0" required
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-gray-50 dark:bg-gray-900 text-gray-800 dark:text-gray-200 focus:outline-none focus:ring-emerald-500 focus:border-emerald-500">
            </div>

            <div class="mb-4">
                <label for="image" class="block text-gray-700 dark:text-gray-200 font-medium mb-1">Product Image</label>
                <input type="file" name="image" id="image" accept="image/*"
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-gray-50 dark:bg-gray-900 text-gray-800 dark:text-gray-200">
            </div>

            <div class="mb-4 flex gap-6">
                <label class="flex items-center gap-2 text-gray-700 dark:text-gray-200">
                    <input type="checkbox" name="is_featured" value="1" class="form-checkbox">
                    Featured Product
                </label>
                <label class="flex items-center gap-2 text-gray-700 dark:text-gray-200">
                    <input type="checkbox" name="is_active" value="1" checked class="form-checkbox">
                    Active
                </label>
            </div>

            <div class="flex gap-4 mt-6">
                <button type="submit" class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg font-medium transition">Create Product</button>
                <a href="<?php echo APP_URL; ?>/admin/products" class="px-6 py-2.5 bg-gray-500 hover:bg-gray-600 text-white rounded-lg font-medium transition">Cancel</a>
            </div>
        </form>
    </div>
</div>

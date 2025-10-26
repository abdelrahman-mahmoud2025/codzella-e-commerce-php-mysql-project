<h1 class="text-3xl font-bold text-gray-800 dark:text-gray-100 mt-8 mb-6 text-center">Manage Categories</h1>

<div class="mb-6">
    <a href="<?php echo APP_URL; ?>/admin/createCategory" class="px-4 py-2 bg-green-500 hover:bg-green-600 text-white rounded-lg transition">Add New Category</a>
</div>

<?php if (!empty($categories)): ?>
    <div class="overflow-x-auto">
        <table class="min-w-full bg-white dark:bg-gray-800 rounded-2xl shadow-md border border-gray-200 dark:border-gray-700">
            <thead>
                <tr class="bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-100">
                    <th class="px-4 py-3 text-left">Name</th>
                    <th class="px-4 py-3 text-left">Description</th>
                    <th class="px-4 py-3 text-center">Status</th>
                    <th class="px-4 py-3 text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($categories as $category): ?>
                    <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-900">
                        <td class="px-4 py-3 font-semibold"><?php echo htmlspecialchars($category['name']); ?></td>
                        <td class="px-4 py-3"><?php echo htmlspecialchars($category['slug']); ?></td>
                        <td class="px-4 py-3 text-center">
                            <span class="px-3 py-1 rounded-full text-white <?php echo $category['is_active'] ? 'bg-green-500' : 'bg-red-500'; ?>">
                                <?php echo $category['is_active'] ? 'Active' : 'Inactive'; ?>
                            </span>
                        </td>
                        <td class="px-4 py-3 text-center flex justify-center gap-2">
                            <a href="<?php echo APP_URL; ?>/admin/editCategory/<?php echo $category['id']; ?>" class="px-3 py-1 bg-blue-500 hover:bg-blue-600 text-white rounded-lg transition">Edit</a>
                            <a href="<?php echo APP_URL; ?>/admin/deleteCategory/<?php echo $category['id']; ?>" class="px-3 py-1 bg-red-500 hover:bg-red-600 text-white rounded-lg transition" onclick="return confirm('Delete this category?')">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php else: ?>
    <p class="text-center text-gray-600 dark:text-gray-400 mt-6">No categories found.</p>
<?php endif; ?>

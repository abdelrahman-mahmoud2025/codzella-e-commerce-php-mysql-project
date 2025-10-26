<h1 class="text-3xl font-bold text-gray-800 dark:text-gray-100 mt-8 mb-6 text-center">Create Category</h1>

<div class="max-w-md mx-auto">
    <div class="bg-white dark:bg-gray-800 shadow-md rounded-xl p-6">
        <form method="POST" action="<?php echo APP_URL; ?>/admin/createCategory">
            <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">

            <div class="mb-4">
                <label for="name" class="block text-gray-700 dark:text-gray-200 font-semibold mb-1">Category Name</label>
                <input type="text" name="name" id="name" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-100" required>
            </div>

            <div class="mb-4">
                <label for="description" class="block text-gray-700 dark:text-gray-200 font-semibold mb-1">Description</label>
                <textarea name="description" id="description" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-100" rows="4"></textarea>
            </div>

            <div class="mb-4">
                <label class="inline-flex items-center">
                    <input type="checkbox" name="is_active" value="1" checked class="form-checkbox text-green-500">
                    <span class="ml-2 text-gray-700 dark:text-gray-200">Active</span>
                </label>
            </div>

            <div class="flex gap-4">
                <button type="submit" class="px-4 py-2 bg-green-500 hover:bg-green-600 text-white rounded-lg transition">Create Category</button>
                <a href="<?php echo APP_URL; ?>/admin/categories" class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition">Cancel</a>
            </div>
        </form>
    </div>
</div>

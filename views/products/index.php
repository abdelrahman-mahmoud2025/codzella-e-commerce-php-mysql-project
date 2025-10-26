<main class="container mx-auto px-4 py-10">
    <h1 class="text-3xl font-bold mb-8 text-gray-900 dark:text-gray-100">Products</h1>

    <!-- 🔍 Search Bar -->
    <section class="mb-10">
        <form 
            method="GET" 
            action="<?php echo APP_URL; ?>/products" 
            class="flex flex-col sm:flex-row gap-4"
        >
            <input 
                type="text" 
                name="search" 
                placeholder="Search products..." 
                value="<?php echo $_GET['search'] ?? ''; ?>" 
                class="flex-1 px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-emerald-500 transition"
            >
            <button 
                type="submit" 
                class="px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-xl transition"
            >
                Search
            </button>
        </form>
    </section>

    <!-- 🛍️ Product Grid -->
    <?php if (!empty($products)): ?>
        <section class="grid sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
            <?php foreach ($products as $product): ?>
                <article class="bg-white dark:bg-gray-800 rounded-2xl shadow-md hover:shadow-lg transition overflow-hidden">
                    <!-- 🖼️ Product Image -->
                    <?php if ($product['image']): ?>
                        <img 
                            src="<?php echo APP_URL; ?>/uploads/<?php echo $product['image']; ?>" 
                            alt="<?php echo htmlspecialchars($product['name']); ?>" 
                            class="w-full h-56 object-cover"
                        >
                    <?php else: ?>
                        <div class="w-full h-56 bg-gray-200 dark:bg-gray-700 flex items-center justify-center text-gray-500">
                            No Image
                        </div>
                    <?php endif; ?>

                    <!-- 🧾 Product Info -->
                    <div class="p-5">
                        <h3 class="text-lg font-semibold mb-2 text-gray-900 dark:text-gray-100">
                            <?php echo htmlspecialchars($product['name']); ?>
                        </h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-3 leading-relaxed">
                            <?php echo htmlspecialchars(substr($product['description'], 0, 100)); ?>...
                        </p>
                        <p class="text-lg font-bold text-emerald-600 dark:text-emerald-400 mb-4">
                            $<?php echo number_format($product['sale_price'] ?? $product['price'], 2); ?>
                        </p>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">
                            Stock: <?php echo $product['stock']; ?>
                        </p>
                        <a 
                            href="<?php echo APP_URL; ?>/products/show/<?php echo $product['slug']; ?>" 
                            class="inline-block w-full text-center px-4 py-2 rounded-lg bg-emerald-600 text-white font-medium hover:bg-emerald-700 transition"
                        >
                            View Details
                        </a>
                    </div>
                </article>
            <?php endforeach; ?>
        </section>

        <!-- 📄 Pagination -->
        <?php if ($totalPages > 1): ?>
            <nav class="flex justify-center gap-2 mt-10">
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <a 
                        href="?page=<?php echo $i; ?>" 
                        class="px-4 py-2 rounded-lg border text-sm font-medium transition 
                            <?php echo $i === $currentPage 
                                ? 'bg-emerald-600 text-white border-emerald-600 hover:bg-emerald-700' 
                                : 'bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-700 text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700'; ?>"
                    >
                        <?php echo $i; ?>
                    </a>
                <?php endfor; ?>
            </nav>
        <?php endif; ?>

    <?php else: ?>
        <div class="text-center py-20">
            <h2 class="text-2xl font-semibold text-gray-800 dark:text-gray-200 mb-2">No products found</h2>
            <p class="text-gray-500 dark:text-gray-400 mb-6">Try searching with different keywords or browse all categories.</p>
            <a 
                href="<?php echo APP_URL; ?>/products" 
                class="inline-block px-6 py-3 rounded-lg border border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-gray-800 text-gray-800 dark:text-gray-200 hover:bg-gray-200 dark:hover:bg-gray-700 transition"
            >
                Back to Products
            </a>
        </div>
    <?php endif; ?>
</main>

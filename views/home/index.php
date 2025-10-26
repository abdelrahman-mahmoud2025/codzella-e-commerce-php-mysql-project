<h1 class="text-4xl font-bold text-center mt-10 mb-4 text-emerald-600 dark:text-emerald-400">
    Welcome to CodeZilla Store
</h1>
<p class="text-center text-gray-600 dark:text-gray-400 mb-12">
    Your one-stop shop for digital products
</p>

<?php if (!empty($featuredProducts)): ?>
<section class="my-16">
    <h2 class="text-2xl font-semibold mb-6 text-gray-800 dark:text-gray-200 border-l-4 border-emerald-500 pl-3">
        Featured Products
    </h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
        <?php foreach ($featuredProducts as $product): ?>
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-md hover:shadow-lg transition overflow-hidden">
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
                
                <div class="p-5">
                    <h3 class="text-lg font-semibold mb-2 text-gray-900 dark:text-gray-100">
                        <?php echo htmlspecialchars($product['name']); ?>
                    </h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">
                        <?php echo htmlspecialchars(substr($product['description'], 0, 100)); ?>...
                    </p>
                    <p class="text-lg font-bold text-emerald-600 dark:text-emerald-400 mb-4">
                        $<?php echo number_format($product['sale_price'] ?? $product['price'], 2); ?>
                    </p>
                    <a 
                        href="<?php echo APP_URL; ?>/products/show/<?php echo $product['slug']; ?>" 
                        class="inline-block w-full text-center px-4 py-2 rounded-lg bg-emerald-600 text-white font-medium hover:bg-emerald-700 transition"
                    >
                        View Details
                    </a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>


<?php if (!empty($latestProducts)): ?>
<section class="my-16">
    <h2 class="text-2xl font-semibold mb-6 text-gray-800 dark:text-gray-200 border-l-4 border-emerald-500 pl-3">
        Latest Products
    </h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
        <?php foreach ($latestProducts as $product): ?>
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-md hover:shadow-lg transition overflow-hidden">
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

                <div class="p-5">
                    <h3 class="text-lg font-semibold mb-2 text-gray-900 dark:text-gray-100">
                        <?php echo htmlspecialchars($product['name']); ?>
                    </h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">
                        <?php echo htmlspecialchars(substr($product['description'], 0, 100)); ?>...
                    </p>
                    <p class="text-lg font-bold text-emerald-600 dark:text-emerald-400 mb-4">
                        $<?php echo number_format($product['sale_price'] ?? $product['price'], 2); ?>
                    </p>
                    <a 
                        href="<?php echo APP_URL; ?>/products/show/<?php echo $product['slug']; ?>" 
                        class="inline-block w-full text-center px-4 py-2 rounded-lg bg-emerald-600 text-white font-medium hover:bg-emerald-700 transition"
                    >
                        View Details
                    </a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>

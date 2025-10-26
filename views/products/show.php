<main class="container mx-auto px-4 py-8">
    <!-- 🧾 Product Details Section -->
    <section class="grid md:grid-cols-2 gap-8 my-8">
        <!-- 📸 Product Image -->
        <div>
            <?php if ($product['image']): ?>
                <img 
                    src="<?php echo APP_URL; ?>/uploads/<?php echo $product['image']; ?>" 
                    alt="<?php echo htmlspecialchars($product['name']); ?>" 
                    class="w-full rounded-2xl shadow-md object-cover"
                >
            <?php else: ?>
                <div class="w-full h-[400px] flex items-center justify-center bg-gray-200 dark:bg-gray-700 rounded-2xl text-gray-500">
                    No Image
                </div>
            <?php endif; ?>
        </div>

        <!-- 🧠 Product Info -->
        <div>
            <h1 class="text-3xl font-bold mb-2 text-gray-900 dark:text-gray-100">
                <?php echo htmlspecialchars($product['name']); ?>
            </h1>
            <p class="text-gray-600 dark:text-gray-400 mb-4">
                Category: <?php echo htmlspecialchars($product['category_name']); ?>
            </p>

            <!-- 💰 Price Section -->
            <div class="my-6">
                <h2 class="text-2xl font-semibold text-emerald-600 dark:text-emerald-400">
                    $<?php echo number_format($product['sale_price'] ?? $product['price'], 2); ?>
                    <?php if ($product['sale_price']): ?>
                        <span class="line-through text-gray-400 text-lg ml-2">
                            $<?php echo number_format($product['price'], 2); ?>
                        </span>
                    <?php endif; ?>
                </h2>
            </div>

            <!-- 📝 Description -->
            <p class="text-gray-700 dark:text-gray-300 leading-relaxed mb-4">
                <?php echo nl2br(htmlspecialchars($product['description'])); ?>
            </p>

            <!-- 📦 Stock Info -->
            <p class="mb-6 text-gray-800 dark:text-gray-200">
                <strong>Stock:</strong> <?php echo $product['stock']; ?> available
            </p>

            <!-- 🛒 Add to Cart -->
            <?php if ($product['stock'] > 0): ?>
                <form method="POST" action="<?php echo APP_URL; ?>/cart/add" class="flex items-center gap-4">
                    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                    <input 
                        type="number" 
                        name="quantity" 
                        value="1" 
                        min="1" 
                        max="<?php echo $product['stock']; ?>" 
                        class="w-24 border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-emerald-600 text-emerald-600 dark:bg-gray-800 dark:text-white"
                    >
                    <button 
                        type="submit" 
                        class="bg-emerald-600 text-white px-5 py-2 rounded-lg hover:bg-emerald-700 transition font-medium"
                    >
                        Add to Cart
                    </button>
                </form>
            <?php else: ?>
                <p class="text-red-600 font-semibold">Out of Stock</p>
            <?php endif; ?>
        </div>
    </section>

    <!-- 🤝 Related Products Section -->
    <?php if (!empty($relatedProducts)): ?>
        <section class="my-16">
            <h2 class="text-2xl font-bold mb-6 text-gray-900 dark:text-gray-100">
                Related Products
            </h2>
            <div class="grid sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                <?php foreach ($relatedProducts as $relatedProduct): ?>
                    <?php if ($relatedProduct['id'] != $product['id']): ?>
                        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-md hover:shadow-lg transition overflow-hidden">
                            <!-- 🖼️ Related Product Image -->
                            <?php if ($relatedProduct['image']): ?>
                                <img 
                                    src="<?php echo APP_URL; ?>/uploads/<?php echo $relatedProduct['image']; ?>" 
                                    alt="<?php echo htmlspecialchars($relatedProduct['name']); ?>" 
                                    class="w-full h-48 object-cover"
                                >
                            <?php else: ?>
                                <div class="w-full h-48 bg-gray-200 dark:bg-gray-700 flex items-center justify-center text-gray-500">
                                    No Image
                                </div>
                            <?php endif; ?>

                            <!-- 🧾 Related Product Info -->
                            <div class="p-4">
                                <h3 class="text-lg font-semibold mb-2 text-gray-900 dark:text-gray-100">
                                    <?php echo htmlspecialchars($relatedProduct['name']); ?>
                                </h3>
                                <p class="text-emerald-600 dark:text-emerald-400 font-medium mb-3">
                                    $<?php echo number_format($relatedProduct['sale_price'] ?? $relatedProduct['price'], 2); ?>
                                </p>
                                <a 
                                    href="<?php echo APP_URL; ?>/products/show/<?php echo $relatedProduct['slug']; ?>" 
                                    class="inline-block w-full text-center px-4 py-2 rounded-lg bg-emerald-600 text-white font-medium hover:bg-emerald-700 transition"
                                >
                                    View Details
                                </a>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </section>
    <?php endif; ?>
</main>
                                
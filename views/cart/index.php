<h1 class="text-4xl font-bold text-emerald-600 dark:text-emerald-400 text-center mt-10 mb-8">
    Shopping Cart
</h1>

<?php if (!empty($cart)): ?>
<div class="my-10 overflow-x-auto">
    <table class="w-full border-collapse bg-white dark:bg-gray-800 rounded-2xl shadow-md overflow-hidden">
        <thead>
            <tr class="bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 uppercase text-sm">
                <th class="py-4 px-6 text-left">Product</th>
                <th class="py-4 px-6 text-center">Price</th>
                <th class="py-4 px-6 text-center">Quantity</th>
                <th class="py-4 px-6 text-center">Subtotal</th>
                <th class="py-4 px-6 text-center">Action</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
            <?php foreach ($cart as $item): ?>
                <tr class="hover:bg-gray-150 dark:hover:bg-gray-750 transition">
                    <td class="py-4 px-6">
                        <div class="flex items-center gap-4">
                            <?php if ($item['image']): ?>
                                <img 
                                    src="<?php echo APP_URL; ?>/uploads/<?php echo $item['image']; ?>" 
                                    alt="<?php echo htmlspecialchars($item['name']); ?>" 
                                    class="w-20 h-20 object-cover rounded-2xl border border-gray-200 dark:border-gray-700"
                                >
                            <?php else: ?>
                                <div class="w-20 h-20 bg-gray-200 dark:bg-gray-700 flex items-center justify-center text-gray-500 rounded-2xl">
                                    No Image
                                </div>
                            <?php endif; ?>
                            <span class="font-semibold text-gray-800 dark:text-gray-100">
                                <?php echo htmlspecialchars($item['name']); ?>
                            </span>
                        </div>
                    </td>

                    <td class="py-4 px-6 text-center text-gray-700 dark:text-gray-300">
                        $<?php echo number_format($item['price'], 2); ?>
                    </td>

                    <td class="py-4 px-6 text-center">
                        <form method="POST" action="<?php echo APP_URL; ?>/cart/update" class="inline-block">
                            <input type="hidden" name="product_id" value="<?php echo $item['id']; ?>">
                            <input 
                                type="number" 
                                name="quantity" 
                                value="<?php echo $item['quantity']; ?>" 
                                min="1" 
                                max="<?php echo $item['stock']; ?>" 
                                onchange="this.form.submit()" 
                                class="w-16 text-center py-1 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-900 text-gray-700 dark:text-gray-200 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                            >
                        </form>
                    </td>

                    <td class="py-4 px-6 text-center font-semibold text-emerald-600 dark:text-emerald-400">
                        $<?php echo number_format($item['price'] * $item['quantity'], 2); ?>
                    </td>

                    <td class="py-4 px-6 text-center">
                        <a 
                            href="<?php echo APP_URL; ?>/cart/remove/<?php echo $item['id']; ?>" 
                            onclick="return confirm('Remove this item?')" 
                            class="text-red-600 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300 font-medium transition"
                        >
                            Remove
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>

        <tfoot class="bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 text-lg font-semibold">
            <tr>
                <td colspan="3" class="py-4 px-6 text-right">Total:</td>
                <td class="py-4 px-6 text-center text-emerald-600 dark:text-emerald-400">
                    $<?php echo number_format($total, 2); ?>
                </td>
                <td></td>
            </tr>
        </tfoot>
    </table>

    <div class="mt-10 flex flex-col md:flex-row justify-between items-center gap-4">
        <a 
            href="<?php echo APP_URL; ?>/products" 
            class="px-6 py-3 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 rounded-2xl font-medium text-gray-800 dark:text-gray-200 transition"
        >
            ← Continue Shopping
        </a>
        <div class="flex gap-3">
            <a 
                href="<?php echo APP_URL; ?>/cart/clear" 
                onclick="return confirm('Clear cart?')" 
                class="px-6 py-3 bg-red-600 hover:bg-red-700 rounded-2xl text-white font-medium transition"
            >
                Clear Cart
            </a>
            <a 
                href="<?php echo APP_URL; ?>/checkout" 
                class="px-6 py-3 bg-emerald-600 hover:bg-emerald-700 rounded-2xl text-white font-medium transition"
            >
                Proceed to Checkout →
            </a>
        </div>
    </div>
</div>

<?php else: ?>
<div class="text-center py-20">
    <h2 class="text-2xl font-semibold text-gray-800 dark:text-gray-200 mb-2">Your cart is empty 🛒</h2>
    <p class="text-gray-600 dark:text-gray-400 mb-6">Add some products to your cart to get started!</p>
    <a 
        href="<?php echo APP_URL; ?>/products" 
        class="inline-block px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white rounded-2xl font-medium transition"
    >
        Browse Products
    </a>
</div>
<?php endif; ?>

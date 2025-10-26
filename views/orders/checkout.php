<h1 class="text-3xl font-bold text-center mb-8">Checkout</h1>

<div class="grid md:grid-cols-2 gap-8">
    <!-- Shipping Information -->
    <div>
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-md p-6">
            <h2 class="text-xl font-semibold mb-6">Shipping Information</h2>
            <form method="POST" action="<?php echo APP_URL; ?>/checkout" class="flex flex-col gap-4">
                <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">

                <div class="flex flex-col gap-1">
                    <label for="shipping_name" class="font-medium">Full Name</label>
                    <input type="text" name="shipping_name" id="shipping_name" required 
                           class="rounded-lg border border-gray-300 dark:border-gray-600 p-3 focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:bg-gray-700 dark:text-white">
                </div>

                <div class="flex flex-col gap-1">
                    <label for="shipping_email" class="font-medium">Email</label>
                    <input type="email" name="shipping_email" id="shipping_email" required
                           class="rounded-lg border border-gray-300 dark:border-gray-600 p-3 focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:bg-gray-700 dark:text-white">
                </div>

                <div class="flex flex-col gap-1">
                    <label for="shipping_phone" class="font-medium">Phone</label>
                    <input type="tel" name="shipping_phone" id="shipping_phone" required
                           class="rounded-lg border border-gray-300 dark:border-gray-600 p-3 focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:bg-gray-700 dark:text-white">
                </div>

                <div class="flex flex-col gap-1">
                    <label for="shipping_address" class="font-medium">Shipping Address</label>
                    <textarea name="shipping_address" id="shipping_address" rows="4" required
                              class="rounded-lg border border-gray-300 dark:border-gray-600 p-3 focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:bg-gray-700 dark:text-white"></textarea>
                </div>

                <div class="flex flex-col gap-1">
                    <label for="notes" class="font-medium">Order Notes (Optional)</label>
                    <textarea name="notes" id="notes" rows="3"
                              class="rounded-lg border border-gray-300 dark:border-gray-600 p-3 focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:bg-gray-700 dark:text-white"></textarea>
                </div>

                <button type="submit" 
                        class="mt-4 w-full bg-emerald-600 hover:bg-emerald-700 text-white font-medium py-3 rounded-lg transition">
                    Place Order
                </button>
            </form>
        </div>
    </div>

    <!-- Order Summary -->
    <div>
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-md p-6">
            <h2 class="text-xl font-semibold mb-6">Order Summary</h2>

            <?php foreach ($cart as $item): ?>
                <div class="flex justify-between py-2 border-b border-gray-200 dark:border-gray-700">
                    <span><?php echo htmlspecialchars($item['name']); ?> × <?php echo $item['quantity']; ?></span>
                    <span class="font-medium">$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></span>
                </div>
            <?php endforeach; ?>

            <div class="flex justify-between mt-4 font-bold text-lg">
                <span>Total:</span>
                <span>$<?php echo number_format($total, 2); ?></span>
            </div>

            <div class="bg-blue-100 dark:bg-blue-900 text-blue-700 dark:text-blue-200 p-4 rounded-lg mt-4 text-sm">
                <strong>💳 Secure Payment:</strong><br>
                After clicking "Place Order", you will be redirected to Stripe's secure payment page to enter your card details.
            </div>

            <div class="bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200 p-3 rounded-lg mt-2 text-sm">
                <strong>🧪 Test Mode:</strong> Use card: <code>4242 4242 4242 4242</code>
            </div>
        </div>
    </div>
</div>

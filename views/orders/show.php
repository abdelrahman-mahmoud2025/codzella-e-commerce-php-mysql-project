<main class="container mx-auto px-4 py-10">
    <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100 mb-8">
        Order Details
    </h1>

    <div class="grid md:grid-cols-3 gap-8 mb-10">
        <!-- 🧾 Order Items -->
        <section class="md:col-span-2 bg-white dark:bg-gray-800 rounded-2xl shadow-md p-6">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-4">
                Order Items
            </h2>
            <div class="divide-y divide-gray-200 dark:divide-gray-700">
                <?php foreach ($orderItems as $item): ?>
                    <div class="flex gap-4 py-4 items-center">
                        <?php if ($item['product_image']): ?>
                            <img 
                                src="<?php echo APP_URL; ?>/uploads/<?php echo $item['product_image']; ?>" 
                                alt="<?php echo htmlspecialchars($item['product_name']); ?>" 
                                class="w-20 h-20 object-cover rounded-lg shadow-sm"
                            >
                        <?php else: ?>
                            <div class="w-20 h-20 flex items-center justify-center bg-gray-200 dark:bg-gray-700 text-gray-500 rounded-lg">
                                No Image
                            </div>
                        <?php endif; ?>

                        <div class="flex-1">
                            <h4 class="font-medium text-gray-900 dark:text-gray-100">
                                <?php echo htmlspecialchars($item['product_name']); ?>
                            </h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                $<?php echo number_format($item['price'], 2); ?> × <?php echo $item['quantity']; ?>
                            </p>
                            <p class="text-emerald-600 dark:text-emerald-400 font-semibold">
                                Subtotal: $<?php echo number_format($item['subtotal'], 2); ?>
                            </p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>

        <!-- 🧭 Order Info + Shipping -->
        <aside class="space-y-6">
            <!-- 📦 Order Info -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-4">
                    Order Information
                </h2>

                <div class="space-y-2 text-gray-700 dark:text-gray-300">
                    <p><strong>Order Number:</strong> <?php echo htmlspecialchars($order['order_number']); ?></p>
                    <p><strong>Date:</strong> <?php echo date('F d, Y H:i', strtotime($order['created_at'])); ?></p>

                    <!-- 🟢 Status -->
                    <p>
                        <strong>Status:</strong><br>
                        <?php
                            $statusColor = match ($order['status']) {
                                'completed' => 'bg-emerald-600',
                                'processing' => 'bg-amber-500',
                                'cancelled' => 'bg-red-600',
                                default => 'bg-gray-500'
                            };
                        ?>
                        <span class="inline-block mt-1 px-3 py-1 rounded-full text-white text-sm font-medium <?php echo $statusColor; ?>">
                            <?php echo ucfirst($order['status']); ?>
                        </span>
                    </p>

                    <!-- 💳 Payment -->
                    <p>
                        <strong>Payment Status:</strong><br>
                        <?php
                            $payColor = match ($order['payment_status']) {
                                'paid' => 'bg-emerald-600',
                                'failed' => 'bg-red-600',
                                default => 'bg-gray-500'
                            };
                        ?>
                        <span class="inline-block mt-1 px-3 py-1 rounded-full text-white text-sm font-medium <?php echo $payColor; ?>">
                            <?php echo ucfirst($order['payment_status']); ?>
                        </span>
                    </p>

                    <p class="mt-3">
                        <strong>Total Amount:</strong><br>
                        <span class="text-2xl font-bold text-emerald-600 dark:text-emerald-400">
                            $<?php echo number_format($order['total_amount'], 2); ?>
                        </span>
                    </p>
                </div>
            </div>

            <!-- 🚚 Shipping -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-4">
                    Shipping Information
                </h2>

                <div class="space-y-2 text-gray-700 dark:text-gray-300">
                    <p><strong>Name:</strong> <?php echo htmlspecialchars($order['shipping_name']); ?></p>
                    <p><strong>Email:</strong> <?php echo htmlspecialchars($order['shipping_email']); ?></p>
                    <p><strong>Phone:</strong> <?php echo htmlspecialchars($order['shipping_phone']); ?></p>
                    <p><strong>Address:</strong><br><?php echo nl2br(htmlspecialchars($order['shipping_address'])); ?></p>

                    <?php if ($order['notes']): ?>
                        <p><strong>Notes:</strong><br><?php echo nl2br(htmlspecialchars($order['notes'])); ?></p>
                    <?php endif; ?>
                </div>
            </div>
        </aside>
    </div>

    <!-- 🔙 Back Button -->
    <div class="mt-8">
        <a 
            href="<?php echo APP_URL; ?>/orders" 
            class="inline-block bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 px-5 py-2 rounded-lg font-medium hover:bg-gray-300 dark:hover:bg-gray-600 transition"
        >
            ← Back to Orders
        </a>
    </div>
</main>

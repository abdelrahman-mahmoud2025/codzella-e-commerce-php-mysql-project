<h1 class="text-3xl font-bold text-gray-800 dark:text-gray-100 mt-8 mb-6 text-center">Order Details</h1>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 my-6">
    <!-- Order Items -->
    <div class="lg:col-span-2">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-md p-6">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-100 mb-4">Order Items</h2>
            <?php foreach ($orderItems as $item): ?>
                <div class="flex gap-4 py-4 border-b border-gray-200 dark:border-gray-700">
                    <?php if ($item['product_image']): ?>
                        <img src="<?php echo APP_URL; ?>/uploads/<?php echo $item['product_image']; ?>" alt="" class="w-20 h-20 object-cover rounded-lg">
                    <?php endif; ?>
                    <div class="flex-1">
                        <h4 class="font-semibold text-gray-800 dark:text-gray-100"><?php echo htmlspecialchars($item['product_name']); ?></h4>
                        <p class="text-gray-700 dark:text-gray-300">Price: $<?php echo number_format($item['price'], 2); ?> × <?php echo $item['quantity']; ?></p>
                        <p class="font-semibold text-emerald-600 dark:text-emerald-400">Subtotal: $<?php echo number_format($item['subtotal'], 2); ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Order & Shipping Info -->
    <div class="flex flex-col gap-4">
        <!-- Order Info -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-md p-6">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-100 mb-4">Order Information</h2>
            <p class="text-gray-700 dark:text-gray-300"><strong>Order Number:</strong> <?php echo htmlspecialchars($order['order_number']); ?></p>
            <p class="text-gray-700 dark:text-gray-300"><strong>Customer:</strong> <?php echo htmlspecialchars($order['user_name']); ?></p>
            <p class="text-gray-700 dark:text-gray-300"><strong>Date:</strong> <?php echo date('F d, Y H:i', strtotime($order['created_at'])); ?></p>
            <p class="text-gray-700 dark:text-gray-300"><strong>Status:</strong> <?php echo ucfirst($order['status']); ?></p>
            <p class="text-gray-700 dark:text-gray-300"><strong>Payment Status:</strong> <?php echo ucfirst($order['payment_status']); ?></p>
            <p class="text-2xl font-bold text-emerald-600 dark:text-emerald-400 mt-2"><strong>Total:</strong> $<?php echo number_format($order['total_amount'], 2); ?></p>
        </div>

        <!-- Shipping Info -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-md p-6">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-100 mb-4">Shipping Info</h2>
            <p class="text-gray-700 dark:text-gray-300"><strong>Name:</strong> <?php echo htmlspecialchars($order['shipping_name']); ?></p>
            <p class="text-gray-700 dark:text-gray-300"><strong>Email:</strong> <?php echo htmlspecialchars($order['shipping_email']); ?></p>
            <p class="text-gray-700 dark:text-gray-300"><strong>Phone:</strong> <?php echo htmlspecialchars($order['shipping_phone']); ?></p>
            <p class="text-gray-700 dark:text-gray-300"><strong>Address:</strong><br><?php echo nl2br(htmlspecialchars($order['shipping_address'])); ?></p>
        </div>
    </div>
</div>

<div class="my-6">
    <a href="<?php echo APP_URL; ?>/admin/orders" class="px-6 py-2.5 bg-gray-500 hover:bg-gray-600 text-white rounded-lg font-medium transition">Back to Orders</a>
</div>

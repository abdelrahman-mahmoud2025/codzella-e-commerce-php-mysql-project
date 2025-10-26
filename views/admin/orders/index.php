<h1 class="text-3xl font-bold text-gray-800 dark:text-gray-100 mt-8 mb-6 text-center">Manage Orders</h1>

<?php if (!empty($orders)): ?>
    <div class="overflow-x-auto">
        <table class="min-w-full bg-white dark:bg-gray-800 rounded-2xl shadow-md border border-gray-200 dark:border-gray-700">
            <thead>
                <tr class="bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-100">
                    <th class="px-4 py-3 text-left">Order #</th>
                    <th class="px-4 py-3 text-left">Customer</th>
                    <th class="px-4 py-3 text-center">Date</th>
                    <th class="px-4 py-3 text-center">Total</th>
                    <th class="px-4 py-3 text-center">Status</th>
                    <th class="px-4 py-3 text-center">Payment</th>
                    <th class="px-4 py-3 text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $order): ?>
                    <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-900">
                        <td class="px-4 py-3"><?php echo htmlspecialchars($order['order_number']); ?></td>
                        <td class="px-4 py-3"><?php echo htmlspecialchars($order['user_name']); ?></td>
                        <td class="px-4 py-3 text-center"><?php echo date('M d, Y', strtotime($order['created_at'])); ?></td>
                        <td class="px-4 py-3 text-center">$<?php echo number_format($order['total_amount'], 2); ?></td>
                        <td class="px-4 py-3 text-center">
                            <form method="POST" action="<?php echo APP_URL; ?>/admin/updateOrderStatus/<?php echo $order['id']; ?>" class="inline">
                                <select name="status" onchange="this.form.submit()" class="px-2 py-1 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100">
                                    <option value="pending" <?php echo $order['status'] === 'pending' ? 'selected' : ''; ?>>Pending</option>
                                    <option value="processing" <?php echo $order['status'] === 'processing' ? 'selected' : ''; ?>>Processing</option>
                                    <option value="completed" <?php echo $order['status'] === 'completed' ? 'selected' : ''; ?>>Completed</option>
                                    <option value="cancelled" <?php echo $order['status'] === 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                                </select>
                            </form>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <?php 
                                $paymentColor = $order['payment_status'] === 'paid' ? 'bg-green-500' : ($order['payment_status'] === 'failed' ? 'bg-red-500' : 'bg-gray-400');
                            ?>
                            <span class="px-3 py-1 rounded-full text-white <?php echo $paymentColor; ?>">
                                <?php echo ucfirst($order['payment_status']); ?>
                            </span>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <a href="<?php echo APP_URL; ?>/admin/viewOrder/<?php echo $order['id']; ?>" class="px-4 py-1 bg-blue-500 hover:bg-blue-600 text-white rounded-lg transition">View</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php else: ?>
    <p class="text-center text-gray-600 dark:text-gray-400 mt-6">No orders found.</p>
<?php endif; ?>

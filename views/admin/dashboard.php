<h1 class="text-3xl font-bold text-gray-800 dark:text-gray-100 mt-8 mb-6">Admin Dashboard</h1>

<!-- Stats Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
    <div class="rounded-2xl p-6 text-white" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%)">
        <h3 class="text-lg font-medium">Total Products</h3>
        <p class="text-3xl font-bold mt-2"><?php echo $stats['total_products']; ?></p>
        <a href="<?php echo APP_URL; ?>/admin/products" class="underline mt-2 inline-block">Manage Products</a>
    </div>

    <div class="rounded-2xl p-6 text-white" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%)">
        <h3 class="text-lg font-medium">Total Orders</h3>
        <p class="text-3xl font-bold mt-2"><?php echo $stats['total_orders']; ?></p>
        <a href="<?php echo APP_URL; ?>/admin/orders" class="underline mt-2 inline-block">Manage Orders</a>
    </div>

    <div class="rounded-2xl p-6 text-white" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%)">
        <h3 class="text-lg font-medium">Total Users</h3>
        <p class="text-3xl font-bold mt-2"><?php echo $stats['total_users']; ?></p>
    </div>

    <div class="rounded-2xl p-6 text-white" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%)">
        <h3 class="text-lg font-medium">Total Revenue</h3>
        <p class="text-3xl font-bold mt-2">$<?php echo number_format($stats['total_revenue'], 2); ?></p>
    </div>
</div>

<!-- Recent Orders Table -->
<div class="bg-white dark:bg-gray-800 rounded-2xl shadow-md p-6 mb-6">
    <h2 class="text-2xl font-semibold text-gray-800 dark:text-gray-100 mb-4">Recent Orders</h2>
    <?php if (!empty($recentOrders)): ?>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200">
                <tr>
                    <th class="px-4 py-3 text-left">Order #</th>
                    <th class="px-4 py-3 text-left">Customer</th>
                    <th class="px-4 py-3 text-center">Date</th>
                    <th class="px-4 py-3 text-center">Total</th>
                    <th class="px-4 py-3 text-center">Status</th>
                    <th class="px-4 py-3 text-center">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                <?php foreach ($recentOrders as $order): ?>
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                    <td class="px-4 py-3"><?php echo htmlspecialchars($order['order_number']); ?></td>
                    <td class="px-4 py-3"><?php echo htmlspecialchars($order['user_name']); ?></td>
                    <td class="px-4 py-3 text-center"><?php echo date('M d, Y', strtotime($order['created_at'])); ?></td>
                    <td class="px-4 py-3 text-center">$<?php echo number_format($order['total_amount'], 2); ?></td>
                    <td class="px-4 py-3 text-center">
                        <span class="px-2 py-1 rounded text-white" style="background: <?php 
                            echo $order['status'] === 'completed' ? '#2ecc71' : 
                                ($order['status'] === 'processing' ? '#f39c12' : '#95a5a6'); 
                        ?>;"><?php echo ucfirst($order['status']); ?></span>
                    </td>
                    <td class="px-4 py-3 text-center">
                        <a href="<?php echo APP_URL; ?>/admin/viewOrder/<?php echo $order['id']; ?>" class="px-3 py-1 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg transition">View</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php else: ?>
        <p class="text-gray-600 dark:text-gray-400">No orders yet.</p>
    <?php endif; ?>
</div>

<!-- Quick Actions & System Status -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-md p-6">
        <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-100">Quick Actions</h3>
        <div class="flex flex-col gap-3 mt-4">
            <a href="<?php echo APP_URL; ?>/admin/createProduct" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg transition">Add New Product</a>
            <a href="<?php echo APP_URL; ?>/admin/createCategory" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg transition">Add New Category</a>
            <a href="<?php echo APP_URL; ?>/admin/orders" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg transition">View All Orders</a>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-md p-6">
        <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-100">System Status</h3>
        <p class="mt-4 text-green-600 dark:text-green-400">✓ Database Connected</p>
        <p class="text-green-600 dark:text-green-400">✓ All Systems Operational</p>
        <p class="text-yellow-600 dark:text-yellow-400">⚠ Pending Orders: <?php echo $stats['pending_orders']; ?></p>
    </div>
</div>

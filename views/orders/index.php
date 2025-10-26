<h1 style="text-align: center; margin-bottom: 2rem;">My Orders</h1>

<?php if (!empty($orders)): ?>
    <div class="orders-table" style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; min-width: 800px;">
            <thead>
                <tr style="background: #0bce90ff; border-bottom: 2px #059669 solid var(--border-color);">
                    <th style="padding: 1rem; text-align: left; color: #fefefe;">Order #</th>
                    <th style="padding: 1rem; text-align: center; color: #fefefe;">Date</th>
                    <th style="padding: 1rem; text-align: center; color: #fefefe;">Total</th>
                    <th style="padding: 1rem; text-align: center; color: #fefefe;">Status</th>
                    <th style="padding: 1rem; text-align: center; color: #fefefe;">Payment</th>
                    <th style="padding: 1rem; text-align: center; color: #fefefe;">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $order): ?>
                    <tr style="border-bottom: 1px solid var(--border-color); transition: background 0.2s;">
                        <td style="padding: 1rem; font-weight: bold;">
                            #<?php echo htmlspecialchars($order['order_number']); ?>
                        </td>
                        <td style="padding: 1rem; text-align: center; color: #555;">
                            <?php echo date('M d, Y', strtotime($order['created_at'])); ?>
                        </td>
                        <td style="padding: 1rem; text-align: center; font-weight: 600;">
                            $<?php echo number_format($order['total_amount'], 2); ?>
                        </td>
                        <td style="padding: 1rem; text-align: center;">
                            <?php 
                                $statusColors = [
                                    'completed' => '#2ecc71',
                                    'processing' => '#f39c12',
                                    'cancelled' => '#e74c3c',
                                    'pending' => '#95a5a6'
                                ];
                            ?>
                            <span style="padding: 0.3rem 0.9rem; border-radius: 20px; color: #fff; background: <?php echo $statusColors[$order['status']] ?? '#95a5a6'; ?>;">
                                <?php echo ucfirst($order['status']); ?>
                            </span>
                        </td>
                        <td style="padding: 1rem; text-align: center;">
                            <?php 
                                $paymentColors = [
                                    'paid' => '#2ecc71',
                                    'failed' => '#e74c3c',
                                    'pending' => '#95a5a6'
                                ];
                            ?>
                            <span style="padding: 0.3rem 0.9rem; border-radius: 20px; color: #fff; background: <?php echo $paymentColors[$order['payment_status']] ?? '#95a5a6'; ?>;">
                                <?php echo ucfirst($order['payment_status']); ?>
                            </span>
                        </td>
                        <td style="padding: 1rem; text-align: center;">
                            <a href="<?php echo APP_URL; ?>/orders/show/<?php echo $order['id']; ?>" 
                               class="btn btn-primary" 
                               style="padding: 0.5rem 1rem; border-radius: 6px; font-size: 0.9rem;">
                               View
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php else: ?>
    <div style="text-align: center; padding: 4rem 0;">
        <h2>No Orders Yet</h2>
        <p>You haven't placed any orders yet. Let's change that!</p>
        <a href="<?php echo APP_URL; ?>/products" class="btn bg-emerald-600" style="margin-top: 1rem;">Start Shopping</a>
    </div>
<?php endif; ?>

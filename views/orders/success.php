<div class="text-center py-16">
    <!-- ✅ Success Icon -->
    <div class="text-6xl text-emerald-600 dark:text-emerald-400 mb-4">✓</div>
    
    <!-- 🏁 Heading -->
    <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100 mb-3">
        Order Successful!
    </h1>

    <!-- 📨 Message -->
    <p class="text-gray-600 dark:text-gray-400 mb-1">
        Thank you for your purchase. Your order has been placed successfully.
    </p>
    <p class="text-gray-600 dark:text-gray-400 mb-8">
        You will receive an email confirmation shortly.
    </p>

    <!-- 🎯 Buttons -->
    <div class="flex flex-wrap gap-4 justify-center">
        <a 
            href="<?php echo APP_URL; ?>/orders" 
            class="px-6 py-2 rounded-lg bg-emerald-600 text-white font-medium hover:bg-emerald-700 transition"
        >
            View My Orders
        </a>
        <a 
            href="<?php echo APP_URL; ?>/products" 
            class="px-6 py-2 rounded-lg border border-emerald-600 text-emerald-600 dark:text-emerald-400 hover:bg-emerald-50 dark:hover:bg-gray-800 transition"
        >
            Continue Shopping
        </a>
    </div>
</div>

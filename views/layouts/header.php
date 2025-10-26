<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title ?? 'CodeZilla Store'; ?> - CodeZilla Digital Store</title>
    <link rel="stylesheet" href="<?php echo APP_URL; ?>/css/style.css">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 text-gray-800 dark:bg-gray-900 dark:text-gray-200 transition-all duration-300">

    <!-- Navbar -->
    <header class="sticky top-0 z-50 bg-white/70 dark:bg-gray-800/70 backdrop-blur-md border-b border-gray-200 dark:border-gray-700">
        <nav class="max-w-7xl mx-auto flex items-center justify-between px-6 py-4">
            <a href="<?php echo APP_URL; ?>/" class="text-2xl font-bold tracking-tight text-emerald-600 dark:text-emerald-400 flex items-center gap-2">
                🛒 <span>CodeZilla Store</span>
            </a>
            <ul class="flex items-center gap-6 text-[15px] font-medium">
                <li><a href="<?php echo APP_URL; ?>/" class="hover:text-emerald-500 transition">Home</a></li>
                <li><a href="<?php echo APP_URL; ?>/products" class="hover:text-emerald-500 transition">Products</a></li>
                <li><a href="<?php echo APP_URL; ?>/cart" class="hover:text-emerald-500 transition">Cart</a></li>

                <?php if (SessionHelper::isLoggedIn()): ?>
                    <li><a href="<?php echo APP_URL; ?>/orders" class="hover:text-emerald-500 transition">My Orders</a></li>
                    <?php if (SessionHelper::isAdmin()): ?>
                        <li><a href="<?php echo APP_URL; ?>/admin/dashboard" class="hover:text-emerald-500 transition">Admin</a></li>
                    <?php endif; ?>
                    <li>
                        <a href="<?php echo APP_URL; ?>/logout" class="px-3 py-1.5 rounded-lg bg-emerald-600 text-white hover:bg-emerald-700 transition">
                            Logout (<?php echo SessionHelper::getUserName(); ?>)
                        </a>
                    </li>
                <?php else: ?>
                    <li><a href="<?php echo APP_URL; ?>/login" class="hover:text-emerald-500 transition">Login</a></li>
                    <li>
                        <a href="<?php echo APP_URL; ?>/register" class="px-3 py-1.5 rounded-lg bg-emerald-600 text-white hover:bg-emerald-700 transition">
                            Register
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-6 py-10 min-h-[70vh]">

        <?php if (SessionHelper::hasFlash('success')): ?>
            <div class="mb-4 p-4 bg-emerald-100 border border-emerald-300 text-emerald-700 rounded-lg">
                <?php echo SessionHelper::getFlash('success'); ?>
            </div>
        <?php endif; ?>

        <?php if (SessionHelper::hasFlash('error')): ?>
            <div class="mb-4 p-4 bg-red-100 border border-red-300 text-red-700 rounded-lg">
                <?php echo SessionHelper::getFlash('error'); ?>
            </div>
        <?php endif; ?>

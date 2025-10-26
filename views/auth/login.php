<div class="max-w-md mx-auto mt-16">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-md overflow-hidden">
        <div class="p-8">
            <h2 class="text-2xl font-bold mb-6 text-gray-800 dark:text-gray-100 text-center">Login</h2>
            <form method="POST" action="<?php echo APP_URL; ?>/login">
                <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">

                <div class="mb-4">
                    <label for="email" class="block mb-2 font-medium text-gray-700 dark:text-gray-200">Email</label>
                    <input 
                        type="email" 
                        name="email" 
                        id="email" 
                        required
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-900 text-gray-800 dark:text-gray-100 focus:ring-emerald-500 focus:border-emerald-500 transition"
                    >
                </div>

                <div class="mb-6">
                    <label for="password" class="block mb-2 font-medium text-gray-700 dark:text-gray-200">Password</label>
                    <input 
                        type="password" 
                        name="password" 
                        id="password" 
                        required
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-900 text-gray-800 dark:text-gray-100 focus:ring-emerald-500 focus:border-emerald-500 transition"
                    >
                </div>

                <button 
                    type="submit" 
                    class="w-full py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-medium rounded-lg transition"
                >
                    Login
                </button>
            </form>

            <p class="mt-4 text-center text-gray-600 dark:text-gray-400">
                Don't have an account? 
                <a href="<?php echo APP_URL; ?>/register" class="text-emerald-600 hover:underline dark:text-emerald-400">Register here</a>
            </p>
        </div>
    </div>
</div>

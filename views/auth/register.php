<div class="max-w-md mx-auto mt-16">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-md overflow-hidden">
        <div class="p-8">
            <h2 class="text-2xl font-bold mb-6 text-gray-800 dark:text-gray-100 text-center">Register</h2>
            <form method="POST" action="<?php echo APP_URL; ?>/register">
                <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">

                <div class="mb-4">
                    <label for="username" class="block mb-2 font-medium text-gray-700 dark:text-gray-200">Username</label>
                    <input 
                        type="text" 
                        name="username" 
                        id="username" 
                        required
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-900 text-gray-800 dark:text-gray-100 focus:ring-emerald-500 focus:border-emerald-500 transition"
                    >
                </div>

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

                <div class="mb-4">
                    <label for="full_name" class="block mb-2 font-medium text-gray-700 dark:text-gray-200">Full Name</label>
                    <input 
                        type="text" 
                        name="full_name" 
                        id="full_name" 
                        required
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-900 text-gray-800 dark:text-gray-100 focus:ring-emerald-500 focus:border-emerald-500 transition"
                    >
                </div>

                <div class="mb-4">
                    <label for="phone" class="block mb-2 font-medium text-gray-700 dark:text-gray-200">Phone</label>
                    <input 
                        type="tel" 
                        name="phone" 
                        id="phone"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-900 text-gray-800 dark:text-gray-100 focus:ring-emerald-500 focus:border-emerald-500 transition"
                    >
                </div>

                <div class="mb-4">
                    <label for="address" class="block mb-2 font-medium text-gray-700 dark:text-gray-200">Address</label>
                    <textarea 
                        name="address" 
                        id="address" 
                        rows="3"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-900 text-gray-800 dark:text-gray-100 focus:ring-emerald-500 focus:border-emerald-500 transition"
                    ></textarea>
                </div>

                <div class="mb-4">
                    <label for="password" class="block mb-2 font-medium text-gray-700 dark:text-gray-200">Password</label>
                    <input 
                        type="password" 
                        name="password" 
                        id="password" 
                        required
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-900 text-gray-800 dark:text-gray-100 focus:ring-emerald-500 focus:border-emerald-500 transition"
                    >
                </div>

                <div class="mb-6">
                    <label for="confirm_password" class="block mb-2 font-medium text-gray-700 dark:text-gray-200">Confirm Password</label>
                    <input 
                        type="password" 
                        name="confirm_password" 
                        id="confirm_password" 
                        required
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-900 text-gray-800 dark:text-gray-100 focus:ring-emerald-500 focus:border-emerald-500 transition"
                    >
                </div>

                <button 
                    type="submit" 
                    class="w-full py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-medium rounded-lg transition"
                >
                    Register
                </button>
            </form>

            <p class="mt-4 text-center text-gray-600 dark:text-gray-400">
                Already have an account? 
                <a href="<?php echo APP_URL; ?>/login" class="text-emerald-600 hover:underline dark:text-emerald-400">Login here</a>
            </p>
        </div>
    </div>
</div>

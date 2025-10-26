<?php
// 🔐 SessionHelper - مساعد إدارة الجلسات (Session)
// بيتعامل مع $_SESSION بطريقة منظمة وآمنة

class SessionHelper {
    
    // 💾 حفظ قيمة في الـ Session
    public static function set($key, $value) {
        // احفظ القيمة في $_SESSION
        $_SESSION[$key] = $value;
    }
    
    // 📖 قراءة قيمة من الـ Session
    public static function get($key, $default = null) {
        // لو المفتاح موجود، ارجع قيمته، لو لأ ارجع القيمة الافتراضية
        return isset($_SESSION[$key]) ? $_SESSION[$key] : $default;
    }
    
    // ✅ تحقق من وجود مفتاح في الـ Session
    public static function has($key) {
        // ارجع true لو المفتاح موجود، false لو مش موجود
        return isset($_SESSION[$key]);
    }
    
    // 🗑️ حذف قيمة من الـ Session
    public static function remove($key) {
        // لو المفتاح موجود، احذفه
        if (isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
        }
    }
    
    // 💥 تدمير الـ Session بالكامل (عند تسجيل الخروج)
    public static function destroy() {
        // دمر الـ Session
        session_destroy();
        // فرغ الـ Array
        $_SESSION = [];
    }
    
    // 🔄 تجديد ID الـ Session (حماية من Session Hijacking)
    public static function regenerate() {
        // غير ID الـ Session (true = احذف الـ Session القديم)
        session_regenerate_id(true);
    }
    
    // ⚡ Flash Messages - رسائل مؤقتة (تظهر مرة واحدة بس)
    
    // 📝 حفظ رسالة Flash
    public static function setFlash($key, $message) {
        // احفظ الرسالة في flash array
        $_SESSION['flash'][$key] = $message;
    }
    
    // 📖 قراءة رسالة Flash (وحذفها بعد القراءة)
    public static function getFlash($key) {
        // لو الرسالة موجودة
        if (isset($_SESSION['flash'][$key])) {
            // اقرأ الرسالة
            $message = $_SESSION['flash'][$key];
            // احذفها (عشان متظهرش تاني)
            unset($_SESSION['flash'][$key]);
            // ارجع الرسالة
            return $message;
        }
        // لو مش موجودة، ارجع null
        return null;
    }
    
    // ✅ تحقق من وجود رسالة Flash
    public static function hasFlash($key) {
        return isset($_SESSION['flash'][$key]);
    }
    
    // 👤 دوال المصادقة (Authentication)
    
    // 💾 حفظ بيانات المستخدم في الـ Session (بعد تسجيل الدخول)
    public static function setUser($user) {
        // احفظ ID المستخدم
        self::set('user_id', $user['id']);
        // احفظ البريد الإلكتروني
        self::set('user_email', $user['email']);
        // احفظ نوع المستخدم (admin أو customer)
        self::set('user_role', $user['role']);
        // احفظ الاسم الكامل
        self::set('user_name', $user['full_name']);
        // جدد ID الـ Session (حماية)
        self::regenerate();
    }
    
    // ✅ تحقق: هل المستخدم مسجل دخول؟
    public static function isLoggedIn() {
        // لو user_id موجود في الـ Session، يبقى مسجل دخول
        return self::has('user_id');
    }
    
    // 👑 تحقق: هل المستخدم أدمن؟
    public static function isAdmin() {
        // لازم يكون مسجل دخول وdور admin
        return self::isLoggedIn() && self::get('user_role') === 'admin';
    }
    
    // 🆔 جيب ID المستخدم
    public static function getUserId() {
        return self::get('user_id');
    }
    
    // 📛 جيب اسم المستخدم
    public static function getUserName() {
        return self::get('user_name');
    }
    
    // 🚪 تسجيل الخروج
    public static function logout() {
        // دمر الـ Session بالكامل
        self::destroy();
    }
    
    // 🛡️ CSRF Protection - حماية من هجمات CSRF
    
    // 🔑 توليد CSRF Token جديد
    public static function generateCsrfToken() {
        // لو مفيش Token موجود، اعمل واحد جديد
        if (!self::has(CSRF_TOKEN_NAME)) {
            // اعمل Token عشوائي (32 بايت = 64 حرف hex)
            self::set(CSRF_TOKEN_NAME, bin2hex(random_bytes(32)));
        }
        // ارجع الـ Token
        return self::get(CSRF_TOKEN_NAME);
    }
    
    // ✅ تحقق من صحة CSRF Token
    public static function verifyCsrfToken($token) {
        // تحقق إن الـ Token موجود وإنه يطابق الـ Token المحفوظ
        // hash_equals = مقارنة آمنة (ضد Timing Attacks)
        return self::has(CSRF_TOKEN_NAME) && hash_equals(self::get(CSRF_TOKEN_NAME), $token);
    }
}

<?php
// ملف الإعدادات الرئيسي للمشروع
// ده ملف مثال (env.example.php) بنسخوه ونسميه (env.php) ونعدل فيه البيانات

// ⚙️ إعدادات قاعدة البيانات
// عنوان سيرفر قاعدة البيانات (في الغالب localhost)
define('DB_HOST', 'localhost');
// اسم قاعدة البيانات
// غير كلمة السر دي بأمانة قوي! (في الإنتاج)
define('DB_NAME', 'codezilla_store');
// اسم المستخدم وكلمة السر بتاعة قاعدة البيانات
define('DB_USER', 'root');
// في الإنتاج، لازم تستخدم كلمة سر قوية
// في الإنتاج، خليها فارغة بالشكل ده: define('DB_PASS', 'كلمه_سر_قويه_جدا_هنا');
define('DB_PASS', '');
// ترميز قاعدة البيانات (يفضل تبقى utf8mb4 عشان تدعم الإيموجيز)
define('DB_CHARSET', 'utf8mb4');

// ⚙️ إعدادات التطبيق
// اسم التطبيق اللي هيظهر في عنوان الموقع
define('APP_NAME', 'CodeZilla Store');
// الرابط الأساسي للموقع
// غير الرابط ده حسب الدومين بتاعك
define('APP_URL', 'http://localhost:8000');
// حالة التطبيق (development أو production)
// في حالة development، هتظهر الأخطاء
// في حالة production، هتتخفي الأخطاء عشان الأمان
define('APP_ENV', 'development'); // development, production

// 💳 إعدادات Stripe (الدفع الإلكتروني)
// المفتاح العام لـ Stripe (بيتكتب في الكود الأمامي)
// خليه فاضي في ملف env.php الحقيقي عشان الأمان
define('STRIPE_PUBLIC_KEY', 'your_stripe_public_key_here');

// المفتاح السري لـ Stripe (سري جداً)
// خليه فاضي في ملف env.php الحقيقي عشان الأمان
define('STRIPE_SECRET_KEY', 'your_stripe_secret_key_here');

// 🔐 إعدادات الجلسات (Sessions)
// مدة صلاحية الجلسة بالثواني (7200 ثانية = ساعتين)
// بعد المدة دي المستخدم هيحتاج يعمل تسجيل دخول تاني
define('SESSION_LIFETIME', 7200);

// 📤 إعدادات رفع الملفات
// المسار اللي هتتحفظ فيه الملفات المرفوعة
// تأكد إن المجلد ده ليه صلاحيات الكتابة
define('UPLOAD_DIR', __DIR__ . '/../uploads/');
// أقصى حجم للملف المسموح برفعه (بالبايت)
// 5 ميجابايت = 5 * 1024 * 1024 = 5,242,880 بايت
define('MAX_FILE_SIZE', 5242880);
// الصيغ المسموح برفعها من الملفات
define('ALLOWED_EXTENSIONS', ['jpg', 'jpeg', 'png', 'gif', 'webp']);

// 🔒 إعدادات الأمان
// اسم الـ CSRF token اللي هيستخدم في الفورمات
define('CSRF_TOKEN_NAME', 'csrf_token');
// أقل طول مسموح بيه لكلمة المرور
define('PASSWORD_MIN_LENGTH', 8);

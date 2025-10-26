أكيد، ده النسخة العربية من README مع إضافة اسمك والرخصة:

````markdown
# 🛒 متجر CodeZilla الرقمي

منصة تجارة إلكترونية كاملة مبنية باستخدام PHP وMySQL عادية، مع تطبيق نمط MVC من الصفر.

## 🌟 المميزات

- ✅ تسجيل دخول وتسجيل مستخدمين (Register, Login, Logout)
- ✅ إدارة المنتجات (إضافة، تعديل، حذف)
- ✅ إدارة الفئات (Categories)
- ✅ نظام سلة المشتريات
- ✅ معالجة الطلبات
- ✅ تكامل مع Stripe للدفع (جاهز)
- ✅ لوحة تحكم المدير
- ✅ تتبع الطلبات
- ✅ تصميم متجاوب (Responsive)
- ✅ ميزات أمان (حماية CSRF، تشفير كلمات المرور، منع SQL Injection)

## 🛠️ التقنيات المستخدمة

- **الواجهة الخلفية**: PHP 8+
- **قاعدة البيانات**: MySQL
- **الدفع**: Stripe API
- **الواجهة الأمامية**: HTML5, CSS3, JavaScript
- **الهندسة**: نمط MVC (تنفيذ يدوي)

## 📋 المتطلبات الأساسية

- PHP 8.0 أو أعلى
- MySQL 5.7 أو أعلى
- Composer
- خادم ويب (Apache/Nginx) أو خادم PHP المدمج

## 🚀 التثبيت

### 1. تحميل المشروع

```bash
cd "c:\Users\Eng Abdelrhman\Herd\PHP store"
````

### 2. تثبيت الاعتمادات

```bash
composer install
```

### 3. إعداد قاعدة البيانات

1. أنشئ قاعدة بيانات باسم `codezilla_store`
2. استورد ملف SQL:

```bash
mysql -u root -p codezilla_store < config/database.sql
```

أو نفّذ الملف يدويًا في phpMyAdmin أو MySQL Workbench.

### 4. تكوين البيئة

1. انسخ `config/env.example.php` إلى `config/env.php`
2. عدّل بيانات الاتصال بقاعدة البيانات:

```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'codezilla_store');
define('DB_USER', 'root');
define('DB_PASS', 'your_password');
```

3. عدّل مفاتيح Stripe:

```php
define('STRIPE_PUBLIC_KEY', 'your_stripe_public_key');
define('STRIPE_SECRET_KEY', 'your_stripe_secret_key');
```

### 5. ضبط الصلاحيات

تأكد أن مجلد `uploads` قابل للكتابة:

```bash
chmod 755 uploads/
```

### 6. تشغيل السيرفر

باستخدام PHP Built-in Server:

```bash
cd public
php -S localhost:8000
```

أو ضبط Apache/Nginx ليشير إلى مجلد `public`.

## 🔐 بيانات تسجيل دخول المدير الافتراضية

* **البريد الإلكتروني**: [admin@codezilla.com](mailto:admin@codezilla.com)
* **كلمة المرور**: admin123

**⚠️ مهم**: غيّر كلمة المرور فور تسجيل الدخول لأول مرة!

## 📁 هيكل المشروع

```
codezilla-store/
├── app/
│   ├── controllers/      # الكنترولرز
│   ├── models/           # نماذج قاعدة البيانات
│   └── helpers/          # المساعدات
├── config/               # ملفات الإعدادات
│   ├── env.php           # إعدادات البيئة
│   ├── database.php      # الاتصال بقاعدة البيانات
│   └── database.sql      # مخطط قاعدة البيانات
├── public/               # جذر الويب العام
│   ├── css/              # ملفات CSS
│   ├── js/               # ملفات JS
│   ├── index.php         # نقطة دخول التطبيق
│   └── .htaccess         # قواعد إعادة الكتابة
├── views/                # قوالب العرض
│   ├── layouts/          # قوالب Layout
│   ├── home/             # صفحات رئيسية
│   ├── auth/             # صفحات التسجيل والدخول
│   ├── products/         # صفحات المنتجات
│   ├── cart/             # صفحات السلة
│   ├── orders/           # صفحات الطلبات
│   └── admin/            # صفحات لوحة المدير
├── uploads/              # الملفات المرفوعة
├── composer.json         # الاعتمادات
└── README.md             # هذا الملف
```

## 🎯 الاستخدام

### ميزات العملاء

1. تصفح المنتجات
2. البحث والتصفية
3. إضافة المنتجات للسلة
4. إتمام عملية الدفع عبر Stripe
5. متابعة الطلبات

### ميزات المدير

1. الدخول للوحة التحكم `/admin/dashboard`
2. إدارة المنتجات
3. إدارة الفئات
4. معالجة الطلبات
5. عرض الإحصائيات

## 🔒 ميزات الأمان

* تشفير كلمات المرور
* منع SQL Injection باستخدام PDO Prepared Statements
* حماية ضد XSS
* حماية CSRF
* إدارة آمنة للجلسات
* التحقق من صلاحية الملفات المرفوعة

## 🧪 الاختبار

* إنشاء حسابات تجريبية
* اختبار الدفع باستخدام بطاقات Stripe التجريبية

## 📝 مسارات الـ API

### المسارات العامة

* `GET /` - الصفحة الرئيسية
* `GET /products` - قائمة المنتجات
* `GET /products/show/{slug}` - تفاصيل المنتج
* `GET /login` - صفحة تسجيل الدخول
* `GET /register` - صفحة التسجيل
* `POST /login` - معالجة تسجيل الدخول
* `POST /register` - معالجة التسجيل

### المسارات المحمية

* `GET /cart` - سلة المشتريات
* `POST /cart/add` - إضافة للسلة
* `GET /checkout` - صفحة الدفع
* `POST /checkout` - معالجة الطلب
* `GET /orders` - تاريخ الطلبات

### مسارات المدير

* `GET /admin/dashboard` - لوحة التحكم
* `GET /admin/products` - إدارة المنتجات
* `GET /admin/orders` - إدارة الطلبات
* `GET /admin/categories` - إدارة الفئات

## 🐛 حل المشاكل

* خطأ اتصال قاعدة البيانات: تحقق من `config/env.php`
* أخطاء 404: تحقق من وجود `.htaccess` وتفعيل mod_rewrite
* مشاكل رفع الملفات: تحقق من صلاحيات `uploads` وإعدادات PHP

## 📚 مصادر التعلم

* [PHP Documentation](https://www.php.net/docs.php)
* [MySQL Documentation](https://dev.mysql.com/doc/)
* [Stripe API Docs](https://stripe.com/docs/api)
* [MVC Pattern](https://en.wikipedia.org/wiki/Model%E2%80%93view%E2%80%93controller)

## 🤝 المساهمة

هذا مشروع تعليمي، يمكنك نسخه والتعديل عليه للتعلم.

## 📄 الترخيص

تم إنشاء المشروع بواسطة **عبدالرحمن محمود**.
المشروع مفتوح المصدر للأغراض التعليمية فقط.

## ⚠️ إخلاء المسؤولية

هذا مشروع تعليمي ولا يجب استخدامه في الإنتاج بدون مراجعات أمان دقيقة.

## 📧 الدعم

لأي استفسار، راجع الوثائق أو افتح Issue في المستودع.

---

**تم الإنشاء ❤️ بواسطة عبدالرحمن محمود للتعلم على PHP & MySQL**
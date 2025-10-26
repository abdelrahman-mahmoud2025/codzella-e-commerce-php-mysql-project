# 🛒 متجر CodeZilla الرقمي

منصة تجارة إلكترونية كاملة مبنية باستخدام PHP وMySQL، مع تطبيق نمط MVC من الصفر.

![PHP](https://img.shields.io/badge/PHP-8.0+-777BB4?style=flat&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-5.7+-4479A1?style=flat&logo=mysql&logoColor=white)
![License](https://img.shields.io/badge/License-Educational-green)

## 📑 جدول المحتويات

- [ملخص سريع للبدء](#-ملخص-سريع-للبدء)
- [المميزات](#-المميزات)
- [التقنيات المستخدمة](#️-التقنيات-المستخدمة)
- [المتطلبات الأساسية](#-المتطلبات-الأساسية)
- [التثبيت والإعداد](#-التثبيت-والإعداد)
- [بيانات المدير الافتراضية](#-بيانات-تسجيل-دخول-المدير-الافتراضية)
- [هيكل المشروع](#-هيكل-المشروع)
- [الاستخدام](#-الاستخدام)
- [حل المشاكل الشائعة](#-حل-المشاكل-الشائعة)
- [للمبتدئين](#-للمبتدئين)

## 🚀 ملخص سريع للبدء

```bash
# 1. انسخ المشروع إلى XAMPP
# ضع المشروع في: c:\xampp\htdocs\codezilla-store

# 2. افتح Terminal في مجلد المشروع ونفّذ:
composer install

# 3. انسخ ملف البيئة:
copy config\env.example.php config\env.php

# 4. افتح phpMyAdmin وأنشئ قاعدة بيانات: codezilla_store
# ثم استورد ملف: config/database.sql

# 5. شغّل Apache و MySQL من XAMPP
# ثم افتح: http://localhost/codezilla-store/public

# تسجيل دخول المدير:
# Email: admin@codezilla.com
# Password: admin123
```

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

## 🚀 التثبيت والإعداد

### 1️⃣ تحميل المشروع

قم بنسخ المشروع إلى مجلد `htdocs` في XAMPP:

```bash
# في حالة استخدام Git
git clone [https://github.com/abdelrahman-mahmoud2025/codzella-e-commerce-php-mysql-project.git] c:\xampp\htdocs\codezilla-store

# أو ضع المشروع مباشرة في المسار:
# c:\xampp\htdocs\codezilla-store
```

### 2️⃣ التأكد من المتطلبات

تأكد من تثبيت:
- ✅ **PHP 8.0+** (يمكن التحقق بتشغيل `php -v`)
- ✅ **Composer** (يمكن التحقق بتشغيل `composer -v`)
- ✅ **MySQL** (مع XAMPP)
- ✅ **Apache** (مع XAMPP)

### 3️⃣ تثبيت الحزم المطلوبة

افتح Terminal/CMD في مجلد المشروع وشغّل:

```bash
cd c:\xampp\htdocs\codezilla-store
composer install
```

> ⚠️ **مهم**: إذا ظهرت رسالة خطأ `composer: command not found`، تأكد من تثبيت Composer من [getcomposer.org](https://getcomposer.org/)

### 4️⃣ إعداد قاعدة البيانات

#### الطريقة الأولى: باستخدام phpMyAdmin (الأسهل)

1. افتح XAMPP Control Panel وشغّل **Apache** و **MySQL**
2. افتح المتصفح واذهب إلى: `http://localhost/phpmyadmin`
3. اضغط على **New** (جديد) لإنشاء قاعدة بيانات
4. اسم القاعدة: `codezilla_store`
5. اختر **Collation**: `utf8mb4_general_ci`
6. اضغط **Create**
7. اختر القاعدة من القائمة اليسرى
8. اضغط على تبويب **Import** (استيراد)
9. اضغط **Choose File** واختر: `config/database.sql`
10. اضغط **Go** (تنفيذ)

#### الطريقة الثانية: باستخدام سطر الأوامر

```bash
# أولاً: أنشئ قاعدة البيانات
mysql -u root -p -e "CREATE DATABASE codezilla_store CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;"

# ثانياً: استورد البيانات
mysql -u root -p codezilla_store < config/database.sql
```

> 💡 **ملاحظة**: إذا لم يكن لديك كلمة مرور لـ MySQL (الافتراضي في XAMPP)، احذف `-p` من الأمر.

### 5️⃣ إعداد ملف البيئة (Environment)

⚠️ **مهم جداً**: هذه الخطوة إلزامية ولا يعمل المشروع بدونها!

1. انسخ ملف `config/env.example.php` وأعد تسميته إلى `config/env.php`:

```bash
# Windows CMD
copy config\env.example.php config\env.php

# أو PowerShell
Copy-Item config\env.example.php config\env.php

# أو يدوياً: افتح مجلد config ثم انسخ env.example.php وأعد تسميته إلى env.php
```

2. افتح ملف `config/env.php` بأي محرر نصوص وعدّل الإعدادات التالية:

```php
// ⚙️ إعدادات قاعدة البيانات
define('DB_HOST', 'localhost');
define('DB_NAME', 'codezilla_store');
define('DB_USER', 'root');
define('DB_PASS', '');  // إذا كان في كلمة مرور لـ MySQL ضعها هنا

// ⚙️ إعدادات التطبيق
define('APP_URL', 'http://localhost:8000');  // أو 'http://localhost/codezilla-store/public'

// 💳 إعدادات Stripe (اختياري - للدفع الإلكتروني)
define('STRIPE_PUBLIC_KEY', 'pk_test_...');  // ضع مفتاح Stripe التجريبي
define('STRIPE_SECRET_KEY', 'sk_test_...');  // ضع المفتاح السري
```

> 💡 **ملاحظة**: يمكنك ترك مفاتيح Stripe فارغة إذا لم تريد تفعيل الدفع الإلكتروني حالياً

### 6️⃣ التأكد من المجلدات المطلوبة

تأكد من وجود مجلد الرفع (سيتم إنشاؤه تلقائياً في أغلب الحالات):

```bash
# إذا لم يكن موجوداً، أنشئه يدوياً
mkdir public\uploads
```

### 7️⃣ تشغيل المشروع

#### ✅ Checklist قبل التشغيل

قبل تشغيل المشروع، تأكد من:
- [ ] تثبيت حزم Composer (`composer install`)
- [ ] إنشاء قاعدة البيانات `codezilla_store`
- [ ] استيراد ملف `config/database.sql`
- [ ] نسخ وتعديل ملف `config/env.php`
- [ ] تشغيل Apache و MySQL من XAMPP

#### الطريقة الأولى: باستخدام XAMPP (موصى بها)

1. شغّل **Apache** و **MySQL** من XAMPP Control Panel
2. افتح المتصفح واذهب إلى: `http://localhost/codezilla-store/public`

> 💡 إذا ظهرت الصفحة الرئيسية بنجاح، تهانينا! 🎉

#### الطريقة الثانية: باستخدام PHP Built-in Server

```bash
cd public
php -S localhost:8000
```

ثم افتح المتصفح: `http://localhost:8000`

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

## 🐛 حل المشاكل الشائعة

### ❌ خطأ "composer: command not found"

**الحل:**
1. حمّل Composer من: [getcomposer.org](https://getcomposer.org/download/)
2. ثبّته على Windows
3. أعد تشغيل Terminal/CMD
4. تحقق بتشغيل: `composer -v`

### ❌ خطأ "SQLSTATE[HY000] [1045] Access denied"

**الحل:**
- تأكد من بيانات MySQL في ملف `config/env.php`:
  ```php
  define('DB_USER', 'root');
  define('DB_PASS', '');  // كلمة المرور الصحيحة
  ```
- تأكد أن MySQL يعمل من XAMPP Control Panel

### ❌ خطأ "Table 'codezilla_store.users' doesn't exist"

**الحل:**
- لم تقم باستيراد قاعدة البيانات بشكل صحيح
- راجع خطوة رقم 4️⃣ في قسم التثبيت
- تأكد من استيراد ملف `config/database.sql` في phpMyAdmin

### ❌ خطأ 404 - الصفحة غير موجودة

**الحل:**
1. تأكد من تشغيل Apache من XAMPP
2. تحقق من الرابط: `http://localhost/codezilla-store/public`
3. إذا كنت تستخدم PHP Server، تأكد من التشغيل من داخل مجلد `public`

### ❌ خطأ "Class 'Stripe\Stripe' not found"

**الحل:**
```bash
# شغّل من مجلد المشروع
composer install
```

### ❌ مشكلة رفع الصور

**الحل:**
1. تأكد من وجود مجلد `public/uploads`
2. تحقق من الصلاحيات (Windows عادة لا يحتاج تعديل)
3. تأكد من حجم الصورة أقل من 5MB
4. الصيغ المسموحة: JPG, JPEG, PNG, GIF, WEBP

### ❌ الصفحة بيضاء فارغة

**الحل:**
1. افتح ملف `config/env.php`
2. تأكد من `define('APP_ENV', 'development');`
3. أعد تحميل الصفحة لرؤية الأخطاء
4. تحقق من ملف `error_log` في Apache

### 🆘 طلب المساعدة

إذا واجهت مشكلة أخرى:
1. تأكد من اتباع جميع الخطوات بالترتيب
2. راجع ملف `FULL_PROJECT_GUIDE.md` للمزيد من التفاصيل
3. افتح Issue في المستودع مع تفاصيل المشكلة

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

## 📧 الدعم والتواصل

### 💬 لديك سؤال؟

1. راجع قسم **حل المشاكل الشائعة** أعلاه
2. اطلع على ملف `FULL_PROJECT_GUIDE.md` للدليل الكامل
3. افتح Issue في المستودع مع وصف مفصل للمشكلة

### 📖 ملفات مفيدة في المشروع

- `FULL_PROJECT_GUIDE.md` - دليل شامل بالعربية
- `PROJECT_GUIDE.md` - دليل المشروع
- `PRD.md` - وثيقة متطلبات المنتج

## 🎓 للمبتدئين

إذا كنت جديداً على PHP أو المشاريع الكاملة:

1. **ابدأ بفهم البنية**: اقرأ قسم "هيكل المشروع" أعلاه
2. **اتبع الخطوات بالترتيب**: لا تتجاوز أي خطوة في التثبيت
3. **تعلّم من الكود**: المشروع مكتوب بطريقة واضحة للتعلم
4. **جرّب التعديل**: ابدأ بتعديلات صغيرة وشاهد النتائج
5. **راجع الأخطاء**: استخدم `APP_ENV = development` لرؤية الأخطاء

## ⭐ إذا أعجبك المشروع

- شارك المشروع مع أصدقائك
- ساهم في تحسينه
- استخدمه للتعلم وبناء مهاراتك

---

<div align="center">

**تم الإنشاء بـ ❤️ بواسطة عبدالرحمن محمود**

للتعلم على PHP & MySQL ونمط MVC

[![GitHub](https://img.shields.io/badge/GitHub-Follow-black?style=social&logo=github)](https://github.com)

</div>
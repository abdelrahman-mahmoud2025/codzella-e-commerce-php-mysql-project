# 🛒 دليل المشروع الشامل - متجر CodeZilla

## 📚 المحتويات
1. [نظرة عامة](#overview)
2. [هيكل المشروع](#structure)
3. [شرح Controllers](#controllers)
4. [شرح Models](#models)
5. [شرح Helpers](#helpers)
6. [شرح Views](#views)
7. [أمثلة عملية](#examples)

---

## 🎯 نظرة عامة {#overview}

### إيه المشروع ده؟
متجر إلكتروني كامل مبني بـ PHP من الصفر بدون Framework.

### المميزات:
- ✅ نظام مستخدمين (Login/Register)
- ✅ إدارة منتجات وتصنيفات
- ✅ سلة تسوق
- ✅ نظام طلبات
- ✅ دفع عبر Stripe
- ✅ لوحة تحكم Admin
- ✅ حماية من الهجمات

---

## 📁 هيكل المشروع {#structure}

```
codezilla-store/
├── app/
│   ├── controllers/    # المتحكمات (6 ملفات)
│   ├── models/         # النماذج (4 ملفات)
│   └── helpers/        # المساعدات (4 ملفات)
├── config/
│   ├── env.php         # الإعدادات
│   ├── database.php    # الاتصال بالقاعدة
│   └── database.sql    # هيكل القاعدة
├── public/
│   ├── index.php       # نقطة الدخول
│   ├── css/
│   ├── js/
│   └── uploads/        # صور المنتجات
├── views/              # صفحات HTML (23 ملف)
└── vendor/             # Stripe SDK
```

---

## 🎮 Controllers - المتحكمات {#controllers}

### 1. HomeController.php
**الوظيفة:** الصفحات العامة

**الدوال:**
- `index()` - الصفحة الرئيسية
- `view()` - عرض صفحة
- `redirect()` - إعادة توجيه
- `notFound()` - صفحة 404

**مثال:**
```php
public function index() {
    $products = $this->productModel->getFeatured(6);
    $data = ['products' => $products];
    $this->view('home/index', $data);
}
```

---

### 2. AuthController.php
**الوظيفة:** تسجيل الدخول والتسجيل

**الدوال:**
- `login()` - تسجيل الدخول
- `register()` - التسجيل
- `logout()` - تسجيل الخروج

**مثال عملي:**
```
المستخدم يدخل بياناته:
1. AuthController->login() يستقبل البيانات
2. يتحقق من CSRF Token
3. User Model يتحقق من البيانات
4. لو صح، يحفظ في Session
5. يحول للصفحة الرئيسية
```

---

### 3. ProductController.php
**الوظيفة:** عرض المنتجات للعملاء

**الدوال:**
- `index()` - قائمة المنتجات (مع بحث)
- `show($slug)` - تفاصيل منتج
- `category($slug)` - منتجات حسب التصنيف

**مثال:**
```php
// المستخدم يبحث عن "laptop"
public function index() {
    $search = $_GET['search'] ?? '';
    $products = $this->productModel->getAll(['search' => $search]);
    $this->view('products/index', ['products' => $products]);
}
```

---

### 4. CartController.php
**الوظيفة:** إدارة السلة

**الدوال:**
- `index()` - عرض السلة
- `add()` - إضافة منتج
- `update()` - تحديث الكمية
- `remove($id)` - حذف منتج
- `clear()` - تفريغ السلة

**مثال:**
```php
// إضافة منتج للسلة
public function add() {
    $productId = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    CartHelper::add($productId, $quantity);
    $this->redirect('/cart');
}
```

---

### 5. OrderController.php
**الوظيفة:** الطلبات والدفع

**الدوال:**
- `index()` - طلبات المستخدم
- `show($id)` - تفاصيل طلب
- `checkout()` - صفحة الدفع
- `success()` - بعد الدفع الناجح
- `cancel()` - إلغاء الدفع

**سير العملية:**
```
1. المستخدم في صفحة Checkout
2. يملأ معلومات الشحن
3. يضغط "Place Order"
4. OrderController ينشئ الطلب
5. يحول لـ Stripe للدفع
6. بعد الدفع، يرجع لصفحة Success
7. OrderController يحدث حالة الطلب
```

---

### 6. AdminController.php
**الوظيفة:** لوحة التحكم (الأكبر)

**الدوال:**

**Dashboard:**
- `dashboard()` - الصفحة الرئيسية

**المنتجات:**
- `products()` - قائمة المنتجات
- `createProduct()` - إضافة منتج
- `editProduct($id)` - تعديل منتج
- `deleteProduct($id)` - حذف منتج

**التصنيفات:**
- `categories()` - قائمة التصنيفات
- `createCategory()` - إضافة تصنيف
- `editCategory($id)` - تعديل تصنيف
- `deleteCategory($id)` - حذف تصنيف

**الطلبات:**
- `orders()` - قائمة الطلبات
- `viewOrder($id)` - تفاصيل طلب
- `updateOrderStatus($id)` - تحديث الحالة

---

## 💾 Models - النماذج {#models}

### 1. User.php
**الوظيفة:** التعامل مع جدول users

**الدوال الرئيسية:**
```php
findById($id)              // البحث بالـ ID
findByEmail($email)        // البحث بالبريد
register($data)            // تسجيل مستخدم جديد
login($email, $password)   // تسجيل الدخول
update($id, $data)         // تحديث البيانات
```

**مثال:**
```php
// تسجيل دخول
public function login($email, $password) {
    $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    
    if ($user && password_verify($password, $user['password'])) {
        return $user;  // نجح
    }
    return false;  // فشل
}
```

---

### 2. Product.php
**الوظيفة:** التعامل مع جدول products

**الدوال:**
```php
findById($id)              // البحث بالـ ID
findBySlug($slug)          // البحث بالـ slug
getAll($filters)           // جميع المنتجات (مع فلترة)
getFeatured($limit)        // المنتجات المميزة
create($data)              // إضافة منتج
update($id, $data)         // تحديث منتج
updateStock($id, $qty)     // تحديث المخزون
```

**مثال:**
```php
// جيب المنتجات المميزة
public function getFeatured($limit = 10) {
    $stmt = $this->db->prepare("
        SELECT * FROM products 
        WHERE is_featured = 1 AND is_active = 1 
        LIMIT ?
    ");
    $stmt->execute([$limit]);
    return $stmt->fetchAll();
}
```

---

### 3. Order.php
**الوظيفة:** التعامل مع orders و order_items

**الدوال:**
```php
create($orderData, $items)     // إنشاء طلب
findById($id)                  // البحث بالـ ID
getOrderItems($orderId)        // منتجات الطلب
updateStatus($id, $status)     // تحديث الحالة
updatePaymentStatus()          // تحديث حالة الدفع
getTotalRevenue()              // إجمالي الإيرادات
```

**مثال:**
```php
// إنشاء طلب جديد
public function create($orderData, $items) {
    $this->db->beginTransaction();
    
    // 1. أنشئ الطلب
    $orderNumber = 'ORD-' . date('Ymd') . '-' . uniqid();
    $stmt = $this->db->prepare("
        INSERT INTO orders (user_id, order_number, total_amount, ...)
        VALUES (?, ?, ?, ...)
    ");
    $stmt->execute([...]);
    $orderId = $this->db->lastInsertId();
    
    // 2. أضف المنتجات
    foreach ($items as $item) {
        $stmt = $this->db->prepare("
            INSERT INTO order_items (order_id, product_id, ...)
            VALUES (?, ?, ...)
        ");
        $stmt->execute([...]);
    }
    
    $this->db->commit();
    return $orderId;
}
```

---

## 🛠️ Helpers - المساعدات {#helpers}

### 1. SessionHelper.php
**الوظيفة:** إدارة الجلسات

**الدوال:**
```php
// Session العادية
set($key, $value)
get($key)
has($key)
remove($key)

// Flash Messages
setFlash($key, $msg)
getFlash($key)

// المستخدم
setUser($user)
isLoggedIn()
isAdmin()
getUserId()
logout()

// CSRF Protection
generateCsrfToken()
verifyCsrfToken($token)
```

**مثال:**
```php
// حفظ رسالة مؤقتة
SessionHelper::setFlash('success', 'تم بنجاح!');

// في الصفحة التالية
$message = SessionHelper::getFlash('success');
// يطبع: "تم بنجاح!" ثم يحذفها
```

---

### 2. ValidationHelper.php
**الوظيفة:** التحقق من البيانات

**القواعد المدعومة:**
- `required` - حقل مطلوب
- `email` - بريد صحيح
- `min:X` - حد أدنى
- `max:X` - حد أقصى
- `numeric` - رقم فقط
- `match:field` - يطابق حقل آخر
- `unique:table,column` - فريد في القاعدة

**مثال:**
```php
$data = $_POST;
$rules = [
    'email' => 'required|email|unique:users,email',
    'password' => 'required|min:8',
    'confirm_password' => 'required|match:password'
];

if ($validator->validate($data, $rules)) {
    // البيانات صحيحة
} else {
    // في أخطاء
    echo $validator->getFirstError();
}
```

---

### 3. CartHelper.php
**الوظيفة:** إدارة السلة

**الدوال:**
```php
add($productId, $quantity)     // إضافة منتج
update($productId, $quantity)  // تحديث الكمية
remove($productId)             // حذف منتج
getCart()                      // الحصول على السلة
getTotal()                     // المجموع الكلي
getCount()                     // عدد المنتجات
isEmpty()                      // هل فارغة؟
clear()                        // تفريغ السلة
validateStock()                // تحقق من المخزون
```

**مثال:**
```php
// السلة محفوظة في Session
$_SESSION['cart'] = [
    1 => ['id' => 1, 'name' => 'Laptop', 'price' => 1000, 'quantity' => 2],
    5 => ['id' => 5, 'name' => 'Mouse', 'price' => 20, 'quantity' => 1]
];

// المجموع
$total = CartHelper::getTotal();  // 2020
```

---

### 4. StripeHelper.php
**الوظيفة:** الدفع عبر Stripe

**الدوال:**
```php
createCheckoutSession($orderData, $lineItems)  // إنشاء جلسة دفع
retrieveSession($sessionId)                    // استرجاع جلسة
formatLineItems($cartItems)                    // تنسيق المنتجات
```

**مثال:**
```php
// إنشاء جلسة دفع
$lineItems = StripeHelper::formatLineItems($cart);
$session = StripeHelper::createCheckoutSession($orderData, $lineItems);

// التحويل لصفحة Stripe
header("Location: " . $session->url);
```

---

## 🎨 Views - صفحات العرض {#views}

### الهيكل:
```
views/
├── layouts/
│   ├── header.php    # رأس الصفحة (يظهر في كل صفحة)
│   └── footer.php    # ذيل الصفحة
├── home/
│   └── index.php     # الصفحة الرئيسية
├── auth/
│   ├── login.php     # تسجيل الدخول
│   └── register.php  # التسجيل
├── products/
│   ├── index.php     # قائمة المنتجات
│   └── show.php      # تفاصيل منتج
├── cart/
│   └── index.php     # السلة
├── orders/
│   ├── checkout.php  # الدفع
│   ├── success.php   # النجاح
│   └── index.php     # طلباتي
└── admin/
    ├── dashboard.php
    ├── products/
    ├── categories/
    └── orders/
```

---

## 💡 أمثلة عملية {#examples}

### مثال 1: المستخدم يشتري منتج

```
1. يفتح الموقع
   → HomeController->index()
   → يعرض المنتجات المميزة

2. يضغط على منتج
   → ProductController->show('laptop')
   → يعرض تفاصيل المنتج

3. يضغط "Add to Cart"
   → CartController->add()
   → يضيف المنتج للسلة (Session)

4. يضغط "Checkout"
   → OrderController->checkout()
   → يملأ معلومات الشحن

5. يضغط "Place Order"
   → Order Model ينشئ الطلب
   → StripeHelper ينشئ جلسة دفع
   → يحول لصفحة Stripe

6. يدفع على Stripe
   → Stripe يحول لصفحة Success
   → OrderController->success()
   → يحدث حالة الطلب لـ "paid"
```

---

### مثال 2: الأدمن يضيف منتج

```
1. يسجل دخول كأدمن
   → AuthController->login()
   → يتحقق من is_admin = 1

2. يذهب للوحة التحكم
   → AdminController->dashboard()
   → يعرض الإحصائيات

3. يضغط "Add Product"
   → AdminController->createProduct()
   → يعرض النموذج

4. يملأ البيانات ويرفع صورة
   → AdminController->createProduct() (POST)
   → يرفع الصورة لـ public/uploads/
   → Product Model يحفظ في القاعدة

5. المنتج يظهر في الموقع
   → ProductController->index()
   → يعرض المنتج الجديد
```

---

### مثال 3: حماية من SQL Injection

```php
// ❌ خطأ (غير آمن)
$id = $_GET['id'];
$query = "SELECT * FROM products WHERE id = $id";

// ✅ صحيح (آمن)
$id = $_GET['id'];
$stmt = $db->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$id]);
```

---

### مثال 4: حماية من CSRF

```php
// في الـ Form
<input type="hidden" name="csrf_token" 
       value="<?php echo SessionHelper::generateCsrfToken(); ?>">

// في الـ Controller
if (!SessionHelper::verifyCsrfToken($_POST['csrf_token'] ?? '')) {
    die('Invalid request');
}
```

---

## 🔒 الأمان والحماية

### 1. SQL Injection
**الحل:** استخدام Prepared Statements
```php
$stmt = $db->prepare("SELECT * FROM users WHERE email = ?");
$stmt->execute([$email]);
```

### 2. XSS (Cross-Site Scripting)
**الحل:** استخدام htmlspecialchars
```php
echo htmlspecialchars($user['name']);
```

### 3. CSRF (Cross-Site Request Forgery)
**الحل:** CSRF Tokens
```php
SessionHelper::generateCsrfToken();
SessionHelper::verifyCsrfToken($token);
```

### 4. Password Security
**الحل:** Hashing
```php
// عند التسجيل
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// عند تسجيل الدخول
password_verify($password, $hashedPassword);
```

---

## 🎯 الخلاصة

### كيف يعمل المشروع؟

```
1. المستخدم يطلب صفحة
   ↓
2. .htaccess يحول الطلب لـ index.php
   ↓
3. index.php يحدد Controller و Method
   ↓
4. Controller يستخدم Model للبيانات
   ↓
5. Controller يستخدم Helper للوظائف المساعدة
   ↓
6. Controller يبعت البيانات للـ View
   ↓
7. View يعرض الصفحة للمستخدم
```

### الملفات الأساسية:
- **Controllers:** تستقبل الطلبات وتتحكم في المنطق
- **Models:** تتعامل مع قاعدة البيانات
- **Helpers:** وظائف مساعدة عامة
- **Views:** صفحات HTML

### نصائح:
1. ✅ استخدم Prepared Statements دائماً
2. ✅ نظف البيانات قبل الحفظ
3. ✅ استخدم CSRF Tokens
4. ✅ Hash الباسوردات
5. ✅ تحقق من الصلاحيات

---

**🎉 دلوقتي فاهم المشروع من أوله لآخره!**

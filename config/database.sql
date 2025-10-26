-- =============================================
-- 🗃️ ملف SQL الخاص بقاعدة بيانات المتجر
-- =============================================

-- إنشاء قاعدة البيانات
-- نستخدم utf8mb4 عشان ندعم كل الحروف والرموز (بما فيها الإيموجيز)
-- لو قاعدة البيانات مش موجودة، هنعملها
-- CHARACTER SET utf8mb4: تدعم كل الحروف والرموز
-- COLLATE utf8mb4_unicode_ci: مقارنة النصوص حساسة للحالة (case insensitive)
CREATE DATABASE IF NOT EXISTS codezilla_store CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
-- نخلي قاعدة البيانات دي هي اللي هنتعامل معاها
USE codezilla_store;


-- =============================================
-- 👥 جدول المستخدمين
-- =============================================
-- لو الجدول مش موجود، هنعمله
-- IF NOT EXISTS: عشان لو الجدول موجود ميحصلش error
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    -- اسم المستخدم (فريد ومش ممكن يكون فارغ)
    username VARCHAR(50) UNIQUE NOT NULL,
    -- الإيميل (فريد ومش ممكن يكون فارغ)
    email VARCHAR(100) UNIQUE NOT NULL,
    -- كلمة المرور (مشفرة باستخدام password_hash)
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    phone VARCHAR(20),
    address TEXT,
    -- صلاحيات المستخدم (عميل أو أدمن)
    role ENUM('customer', 'admin') DEFAULT 'customer',
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    -- فهارس للبحث السريع
    INDEX idx_email (email),  -- للبحث بالإيميل
    INDEX idx_role (role)  -- للبحث حسب الصلاحيات
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- =============================================
-- 📂 جدول التصنيفات
-- =============================================
CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    -- الرابط الودود للتصنيف (مثل: electronics)
    slug VARCHAR(100) UNIQUE NOT NULL,
    description TEXT,
    image VARCHAR(255),
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_slug (slug),
    INDEX idx_active (is_active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- =============================================
-- 🛍️ جدول المنتجات
-- =============================================
CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT NOT NULL,
    name VARCHAR(200) NOT NULL,
    slug VARCHAR(200) UNIQUE NOT NULL,
    description TEXT,
    price DECIMAL(10, 2) NOT NULL,
    sale_price DECIMAL(10, 2),
    stock INT DEFAULT 0,
    image VARCHAR(255),
    -- هل المنتج مميز ويظهر في الصفحة الرئيسية؟ (1 = نعم، 0 = لا)
    is_featured TINYINT(1) DEFAULT 0,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    -- ربط المنتج بالتصنيف التابع له
    -- ON DELETE CASCADE: لو اتمسح التصنيف، هتتمسح كل المنتجات التابعة ليه
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE,
    INDEX idx_category (category_id),
    INDEX idx_slug (slug),
    INDEX idx_active (is_active),
    INDEX idx_featured (is_featured)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- =============================================
-- 🛒 جدول الطلبات
-- =============================================
CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    order_number VARCHAR(50) UNIQUE NOT NULL,
    total_amount DECIMAL(10, 2) NOT NULL,
    -- حالة الطلب
    -- pending: في انتظار المراجعة
    -- processing: قيد التجهيز
    -- completed: مكتمل
    -- cancelled: ملغي
    status ENUM('pending', 'processing', 'completed', 'cancelled') DEFAULT 'pending',
    payment_method VARCHAR(50) DEFAULT 'stripe',
    -- حالة الدفع
    -- pending: في انتظار الدفع
    -- paid: تم الدفع
    -- failed: فشل الدفع
    -- refunded: تم استرداد المبلغ
    payment_status ENUM('pending', 'paid', 'failed', 'refunded') DEFAULT 'pending',
    stripe_payment_id VARCHAR(255),
    shipping_name VARCHAR(100) NOT NULL,
    shipping_email VARCHAR(100) NOT NULL,
    shipping_phone VARCHAR(20),
    shipping_address TEXT NOT NULL,
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user (user_id),
    INDEX idx_order_number (order_number),
    INDEX idx_status (status),
    INDEX idx_payment_status (payment_status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- =============================================
-- 📦 جدول منتجات الطلب
-- =============================================
CREATE TABLE IF NOT EXISTS order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    product_name VARCHAR(200) NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    quantity INT NOT NULL,
    subtotal DECIMAL(10, 2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    -- ربط المنتج بالطلب التابع له
    -- ON DELETE CASCADE: لو اتمسح الطلب، هتتمسح كل المنتجات التابعة ليه
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    INDEX idx_order (order_id),
    INDEX idx_product (product_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- =============================================
-- 📝 بيانات تجريبية
-- =============================================

-- إضافة مستخدم أدمن افتراضي
-- كلمة المرور: admin123 (مشفرة بـ password_hash)
INSERT INTO users (username, email, password, full_name, role) VALUES 
('admin', 'admin@codezilla.com', 'admin123', 'Admin User', 'admin');


-- إضافة تصنيفات تجريبية
INSERT INTO categories (name, slug, description) VALUES 
('Electronics', 'electronics', 'Electronic devices and gadgets'),
('Clothing', 'clothing', 'Fashion and apparel'),
('Books', 'books', 'Books and educational materials'),
('Home & Garden', 'home-garden', 'Home decor and garden supplies');


-- إضافة منتجات تجريبية
INSERT INTO products (category_id, name, slug, description, price, stock, is_featured) VALUES 
(1, 'Wireless Headphones', 'wireless-headphones', 'High-quality wireless headphones with noise cancellation', 99.99, 50, 1),
(1, 'Smart Watch', 'smart-watch', 'Feature-rich smartwatch with fitness tracking', 199.99, 30, 1),
(2, 'Cotton T-Shirt', 'cotton-tshirt', 'Comfortable cotton t-shirt in various colors', 19.99, 100, 0),
(3, 'PHP Programming Book', 'php-programming-book', 'Complete guide to PHP development', 39.99, 25, 1);

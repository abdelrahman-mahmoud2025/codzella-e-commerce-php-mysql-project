<?php
// 📦 Order Model - نموذج الطلبات
// بيتعامل مع جدولين: orders (الطلبات) و order_items (منتجات الطلب)

class Order {
    
    // متغير الاتصال بقاعدة البيانات
    private $db;
    // اسم جدول الطلبات
    private $table = 'orders';
    // اسم جدول منتجات الطلب
    private $itemsTable = 'order_items';
    
    // Constructor - بيشتغل أول ما نعمل كائن من الكلاس
    public function __construct() {
        // جيب الاتصال بقاعدة البيانات
        $this->db = Database::getInstance()->getConnection();
    }
    
    // 🔍 البحث عن طلب بالـ ID (مع بيانات المستخدم)
    public function findById($id) {
        // JOIN مع جدول users عشان نجيب بيانات العميل
        $stmt = $this->db->prepare("
            SELECT o.*, u.email as user_email, u.full_name as user_name 
            FROM {$this->table} o 
            LEFT JOIN users u ON o.user_id = u.id 
            WHERE o.id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    // 🔍 البحث عن طلب برقم الطلب (Order Number)
    public function findByOrderNumber($orderNumber) {
        // رقم الطلب فريد (مثلاً: ORD-20251026-ABC123)
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE order_number = ?");
        $stmt->execute([$orderNumber]);
        return $stmt->fetch();
    }
    
    // 📦 جيب منتجات طلب معين
    public function getOrderItems($orderId) {
        // JOIN مع جدول products عشان نجيب صورة المنتج
        $stmt = $this->db->prepare("
            SELECT oi.*, p.image as product_image 
            FROM {$this->itemsTable} oi 
            LEFT JOIN products p ON oi.product_id = p.id 
            WHERE oi.order_id = ?
        ");
        $stmt->execute([$orderId]);
        return $stmt->fetchAll();
    }
    
    // 👤 جيب كل طلبات مستخدم معين
    public function getUserOrders($userId, $limit = null) {
        // رتب حسب الأحدث
        $sql = "SELECT * FROM {$this->table} WHERE user_id = ? ORDER BY created_at DESC";
        
        // لو في limit (عدد محدد)
        if ($limit) {
            $sql .= " LIMIT {$limit}";
        }
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }
    
    // 📋 جيب كل الطلبات (للأدمن - مع فلترة)
    public function getAll($filters = [], $limit = null, $offset = 0) {
        // استعلام مع JOIN لجلب بيانات العملاء
        $sql = "SELECT o.*, u.email as user_email, u.full_name as user_name 
                FROM {$this->table} o 
                LEFT JOIN users u ON o.user_id = u.id 
                WHERE 1=1";
        $params = [];
        
        // فلتر حسب حالة الطلب (pending, processing, completed, cancelled)
        if (isset($filters['status'])) {
            $sql .= " AND o.status = ?";
            $params[] = $filters['status'];
        }
        
        // فلتر حسب حالة الدفع (pending, paid, failed)
        if (isset($filters['payment_status'])) {
            $sql .= " AND o.payment_status = ?";
            $params[] = $filters['payment_status'];
        }
        
        // رتب حسب الأحدث
        $sql .= " ORDER BY o.created_at DESC";
        
        // ترقيم صفحات
        if ($limit) {
            $sql .= " LIMIT {$limit} OFFSET {$offset}";
        }
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
    
    // ➕ إنشاء طلب جديد (أهم دالة - بتستخدم Transaction!)
    public function create($orderData, $items) {
        try {
            // ابدأ Transaction (عشان لو حصل خطأ نرجع كل حاجة)
            $this->db->beginTransaction();
            
            // ولد رقم طلب فريد (مثلاً: ORD-20251026-ABC123)
            $orderNumber = 'ORD-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -6));
            
            // 1️⃣ أضف الطلب في جدول orders
            $stmt = $this->db->prepare("
                INSERT INTO {$this->table} 
                (user_id, order_number, total_amount, status, payment_method, payment_status, 
                 stripe_payment_id, shipping_name, shipping_email, shipping_phone, shipping_address, notes) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            
            $stmt->execute([
                $orderData['user_id'],  // ID المستخدم
                $orderNumber,  // رقم الطلب
                $orderData['total_amount'],  // المجموع الكلي
                $orderData['status'] ?? 'pending',  // الحالة (افتراضي: pending)
                $orderData['payment_method'] ?? 'stripe',  // طريقة الدفع
                $orderData['payment_status'] ?? 'pending',  // حالة الدفع
                $orderData['stripe_payment_id'] ?? null,  // Stripe session ID
                $orderData['shipping_name'],  // اسم المستلم
                $orderData['shipping_email'],  // بريد المستلم
                $orderData['shipping_phone'] ?? null,  // هاتف المستلم
                $orderData['shipping_address'],  // عنوان الشحن
                $orderData['notes'] ?? null  // ملاحظات
            ]);
            
            // جيب ID الطلب اللي اتضاف
            $orderId = $this->db->lastInsertId();
            
            // 2️⃣ أضف المنتجات في جدول order_items
            $stmt = $this->db->prepare("
                INSERT INTO {$this->itemsTable} 
                (order_id, product_id, product_name, price, quantity, subtotal) 
                VALUES (?, ?, ?, ?, ?, ?)
            ");
            
            // لكل منتج في السلة
            foreach ($items as $item) {
                // أضف المنتج للطلب
                $stmt->execute([
                    $orderId,  // ID الطلب
                    $item['id'],  // ID المنتج
                    $item['name'],  // اسم المنتج (نحفظه عشان لو المنتج اتحذف)
                    $item['price'],  // السعر وقت الشراء
                    $item['quantity'],  // الكمية
                    $item['price'] * $item['quantity']  // المجموع الفرعي
                ]);
                
                // 3️⃣ اطرح الكمية من المخزون
                $product = new Product();
                $product->updateStock($item['id'], $item['quantity']);
            }
            
            // كل حاجة تمام، احفظ التغييرات
            $this->db->commit();
            // ارجع ID الطلب
            return $orderId;
            
        } catch (PDOException $e) {
            // لو حصل خطأ، ارجع كل حاجة (Rollback)
            $this->db->rollBack();
            error_log("إنشاء طلب خطأ: " . $e->getMessage());
            return false;
        }
    }
    
    // 🔄 تحديث حالة الطلب (للأدمن)
    public function updateStatus($id, $status) {
        try {
            // حدث الحالة (pending → processing → completed)
            $stmt = $this->db->prepare("UPDATE {$this->table} SET status = ? WHERE id = ?");
            return $stmt->execute([$status, $id]);
        } catch (PDOException $e) {
            error_log("تحديث حالة طلب خطأ: " . $e->getMessage());
            return false;
        }
    }
    
    // 💳 تحديث حالة الدفع (بعد Stripe)
    public function updatePaymentStatus($id, $paymentStatus, $stripePaymentId = null) {
        try {
            // حدث حالة الدفع و Stripe session ID
            $stmt = $this->db->prepare("
                UPDATE {$this->table} 
                SET payment_status = ?, stripe_payment_id = ? 
                WHERE id = ?
            ");
            return $stmt->execute([$paymentStatus, $stripePaymentId, $id]);
        } catch (PDOException $e) {
            error_log("تحديث حالة دفع خطأ: " . $e->getMessage());
            return false;
        }
    }
    
    // 🔢 احسب عدد الطلبات (مع فلاتر)
    public function count($filters = []) {
        $sql = "SELECT COUNT(*) FROM {$this->table} WHERE 1=1";
        $params = [];
        
        // فلتر حسب الحالة
        if (isset($filters['status'])) {
            $sql .= " AND status = ?";
            $params[] = $filters['status'];
        }
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchColumn();
    }
    
    // 💰 احسب إجمالي الإيرادات (للأدمن)
    public function getTotalRevenue() {
        // اجمع كل الطلبات المدفوعة أو المكتملة
        $stmt = $this->db->query("
            SELECT SUM(total_amount) 
            FROM {$this->table} 
            WHERE payment_status = 'paid' 
            OR status = 'completed'
        ");
        // لو مفيش طلبات، ارجع 0
        return $stmt->fetchColumn() ?? 0;
    }
}

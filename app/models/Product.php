<?php
// 🛍️ Product Model - نموذج المنتجات
// بيتعامل مع جدول products في قاعدة البيانات

class Product {
    
    // متغير الاتصال بقاعدة البيانات
    private $db;
    // اسم الجدول
    private $table = 'products';
    
    // Constructor - بيشتغل أول ما نعمل كائن من الكلاس
    public function __construct() {
        // جيب الاتصال بقاعدة البيانات
        $this->db = Database::getInstance()->getConnection();
    }
    
    // 🔍 البحث عن منتج بالـ ID (مع اسم التصنيف)
    public function findById($id) {
        // JOIN مع جدول categories عشان نجيب اسم التصنيف
        $stmt = $this->db->prepare("
            SELECT p.*, c.name as category_name 
            FROM {$this->table} p 
            LEFT JOIN categories c ON p.category_id = c.id 
            WHERE p.id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    // 🔍 البحث عن منتج بالـ Slug
    public function findBySlug($slug) {
        $stmt = $this->db->prepare("
            SELECT p.*, c.name as category_name 
            FROM {$this->table} p 
            LEFT JOIN categories c ON p.category_id = c.id 
            WHERE p.slug = ?
        ");
        $stmt->execute([$slug]);
        return $stmt->fetch();
    }
    
    // 📋 جيب كل المنتجات (مع فلترة وبحث)
    public function getAll($filters = [], $limit = null, $offset = 0) {
        // استعلام أساسي مع JOIN
        $sql = "SELECT p.*, c.name as category_name 
                FROM {$this->table} p 
                LEFT JOIN categories c ON p.category_id = c.id 
                WHERE 1=1";  // 1=1 عشان نقدر نضيف AND بعدين
        $params = [];
        
        // لو في فلتر حسب التصنيف
        if (isset($filters['category_id'])) {
            $sql .= " AND p.category_id = ?";
            $params[] = $filters['category_id'];
        }
        
        // لو عايز النشطة بس
        if (isset($filters['is_active'])) {
            $sql .= " AND p.is_active = ?";
            $params[] = $filters['is_active'];
        }
        
        // لو عايز المميزة بس
        if (isset($filters['is_featured'])) {
            $sql .= " AND p.is_featured = ?";
            $params[] = $filters['is_featured'];
        }
        
        // لو في بحث (في الاسم أو الوصف)
        if (isset($filters['search'])) {
            $sql .= " AND (p.name LIKE ? OR p.description LIKE ?)";
            $searchTerm = '%' . $filters['search'] . '%';  // % للبحث في أي مكان
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }
        
        // رتب حسب الأحدث
        $sql .= " ORDER BY p.created_at DESC";
        
        // لو في limit (ترقيم صفحات)
        if ($limit) {
            $sql .= " LIMIT {$limit} OFFSET {$offset}";
        }
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
    
    // ⭐ جيب المنتجات المميزة
    public function getFeatured($limit = 8) {
        // استخدم getAll مع فلتر is_featured = 1
        return $this->getAll(['is_featured' => 1, 'is_active' => 1], $limit);
    }
    
    // 🆕 جيب أحدث المنتجات
    public function getLatest($limit = 12) {
        // استخدم getAll بدون فلتر (هيرتب حسب created_at تلقائي)
        return $this->getAll(['is_active' => 1], $limit);
    }
    
    // ➕ إضافة منتج جديد
    public function create($data) {
        try {
            // جهز استعلام الإضافة
            $stmt = $this->db->prepare("
                INSERT INTO {$this->table} 
                (category_id, name, slug, description, price, sale_price, stock, image, is_featured, is_active) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            
            // نفذ الاستعلام بالبيانات
            $stmt->execute([
                $data['category_id'],  // ID التصنيف
                $data['name'],  // اسم المنتج
                $data['slug'],  // الـ slug
                $data['description'] ?? null,  // الوصف
                $data['price'],  // السعر
                $data['sale_price'] ?? null,  // سعر التخفيض
                $data['stock'] ?? 0,  // المخزون
                $data['image'] ?? null,  // الصورة
                $data['is_featured'] ?? 0,  // مميز ولا لأ
                $data['is_active'] ?? 1  // نشط ولا لأ
            ]);
            
            // ارجع ID المنتج الجديد
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            error_log("إضافة منتج خطأ: " . $e->getMessage());
            return false;
        }
    }
    
    // 🔄 تحديث منتج
    public function update($id, $data) {
        try {
            // قائمة الحقول المسموح بتحديثها (حماية)
            $allowedFields = ['category_id', 'name', 'slug', 'description', 'price', 
                            'sale_price', 'stock', 'image', 'is_featured', 'is_active'];
            
            $fields = [];
            $values = [];
            
            // لكل حقل في البيانات
            foreach ($data as $key => $value) {
                // لو الحقل مسموح
                if (in_array($key, $allowedFields)) {
                    $fields[] = "{$key} = ?";
                    $values[] = $value;
                }
            }
            
            // لو مفيش حقول للتحديث
            if (empty($fields)) {
                return false;
            }
            
            $values[] = $id;
            
            // اعمل استعلام UPDATE ديناميكي
            $sql = "UPDATE {$this->table} SET " . implode(', ', $fields) . " WHERE id = ?";
            $stmt = $this->db->prepare($sql);
            
            return $stmt->execute($values);
        } catch (PDOException $e) {
            error_log("تحديث منتج خطأ: " . $e->getMessage());
            return false;
        }
    }
    
    // 📦 تحديث المخزون (بعد البيع)
    public function updateStock($id, $quantity) {
        try {
            // اطرح الكمية من المخزون (stock = stock - quantity)
            $stmt = $this->db->prepare("UPDATE {$this->table} SET stock = stock - ? WHERE id = ?");
            return $stmt->execute([$quantity, $id]);
        } catch (PDOException $e) {
            error_log("تحديث مخزون خطأ: " . $e->getMessage());
            return false;
        }
    }
    
    // ❌ حذف منتج
    public function delete($id) {
        try {
            $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = ?");
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            error_log("حذف منتج خطأ: " . $e->getMessage());
            return false;
        }
    }
    
    // 🔢 احسب عدد المنتجات (مع فلاتر)
    public function count($filters = []) {
        $sql = "SELECT COUNT(*) FROM {$this->table} WHERE 1=1";
        $params = [];
        
        // فلتر حسب التصنيف
        if (isset($filters['category_id'])) {
            $sql .= " AND category_id = ?";
            $params[] = $filters['category_id'];
        }
        
        // فلتر حسب الحالة
        if (isset($filters['is_active'])) {
            $sql .= " AND is_active = ?";
            $params[] = $filters['is_active'];
        }
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchColumn();
    }
    
    // 🔗 توليد Slug من الاسم
    public function generateSlug($name) {
        // حول الاسم لـ slug
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name)));
        
        $originalSlug = $slug;
        $counter = 1;
        
        // لو الـ slug موجود، زود رقم
        while ($this->findBySlug($slug)) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }
        
        return $slug;
    }
}

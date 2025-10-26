<?php
// 🏷️ Category Model - نموذج التصنيفات
// بيتعامل مع جدول categories في قاعدة البيانات

class Category {
    
    // متغير الاتصال بقاعدة البيانات
    private $db;
    // اسم الجدول
    private $table = 'categories';
    
    // Constructor - بيشتغل أول ما نعمل كائن من الكلاس
    public function __construct() {
        // جيب الاتصال بقاعدة البيانات من Database Singleton
        $this->db = Database::getInstance()->getConnection();
    }
    
    // 🔍 البحث عن تصنيف بالـ ID
    public function findById($id) {
        // جهز الاستعلام (Prepared Statement للحماية من SQL Injection)
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE id = ?");
        // نفذ الاستعلام
        $stmt->execute([$id]);
        // ارجع النتيجة (صف واحد)
        return $stmt->fetch();
    }
    
    // 🔍 البحث عن تصنيف بالـ Slug
    public function findBySlug($slug) {
        // جهز الاستعلام
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE slug = ?");
        // نفذ الاستعلام
        $stmt->execute([$slug]);
        // ارجع النتيجة
        return $stmt->fetch();
    }
    
    // 📋 جيب كل التصنيفات
    public function getAll($activeOnly = true) {
        // ابدأ الاستعلام
        $sql = "SELECT * FROM {$this->table}";
        
        // لو عايز النشطة بس (is_active = 1)
        if ($activeOnly) {
            $sql .= " WHERE is_active = 1";
        }
        
        // رتب حسب الاسم (أبجدياً)
        $sql .= " ORDER BY name ASC";
        
        // نفذ الاستعلام
        $stmt = $this->db->query($sql);
        // ارجع كل النتائج (array من الصفوف)
        return $stmt->fetchAll();
    }
    
    // ➕ إضافة تصنيف جديد
    public function create($data) {
        try {
            // جهز استعلام الإضافة
            $stmt = $this->db->prepare("
                INSERT INTO {$this->table} (name, slug, description, image, is_active) 
                VALUES (?, ?, ?, ?, ?)
            ");
            
            // نفذ الاستعلام بالبيانات
            $stmt->execute([
                $data['name'],  // اسم التصنيف
                $data['slug'],  // الـ slug (للـ URL)
                $data['description'] ?? null,  // الوصف (اختياري)
                $data['image'] ?? null,  // الصورة (اختياري)
                $data['is_active'] ?? 1  // نشط ولا لأ (افتراضي = نشط)
            ]);
            
            // ارجع ID التصنيف اللي اتضاف
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            // لو حصل خطأ، سجله
            error_log("Category Create Error: " . $e->getMessage());
            return false;
        }
    }
    
    // 🔄 تحديث تصنيف موجود
    public function update($id, $data) {
        try {
            // arrays للحقول والقيم
            $fields = [];
            $values = [];
            
            // لكل حقل في البيانات
            foreach ($data as $key => $value) {
                // لو مش ID (عشان منحدثوش)
                if ($key !== 'id') {
                    // أضف الحقل للاستعلام (name = ?)
                    $fields[] = "{$key} = ?";
                    // أضف القيمة
                    $values[] = $value;
                }
            }
            
            // أضف ID في الآخر (للـ WHERE)
            $values[] = $id;
            
            // اعمل استعلام UPDATE ديناميكي
            $sql = "UPDATE {$this->table} SET " . implode(', ', $fields) . " WHERE id = ?";
            $stmt = $this->db->prepare($sql);
            
            // نفذ الاستعلام
            return $stmt->execute($values);
        } catch (PDOException $e) {
            // لو حصل خطأ، سجله
            error_log("Category Update Error: " . $e->getMessage());
            return false;
        }
    }
    
    // ❌ حذف تصنيف
    public function delete($id) {
        try {
            // جهز استعلام الحذف
            $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = ?");
            // نفذ الاستعلام
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            // لو حصل خطأ (مثلاً التصنيف مربوط بمنتجات)، سجله
            error_log("Category Delete Error: " . $e->getMessage());
            return false;
        }
    }
    
    // 🔢 احسب عدد التصنيفات
    public function count() {
        // نفذ استعلام COUNT
        $stmt = $this->db->query("SELECT COUNT(*) FROM {$this->table}");
        // ارجع العدد (رقم واحد)
        return $stmt->fetchColumn();
    }
    
    // 🔗 توليد Slug من الاسم
    // الـ Slug بيستخدم في الـ URL (مثلاً: electronics بدل Electronics & Gadgets)
    public function generateSlug($name) {
        // حول الاسم لـ slug (حروف صغيرة، شرطات بدل المسافات)
        // مثلاً: "Electronics & Gadgets" يبقى "electronics-gadgets"
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name)));
        
        // تحقق إن الـ slug مش مكرر
        $originalSlug = $slug;
        $counter = 1;
        
        // لو الـ slug موجود خلاص، زود رقم في الآخر
        while ($this->findBySlug($slug)) {
            // مثلاً: electronics-1, electronics-2, إلخ
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }
        
        // ارجع الـ slug الفريد
        return $slug;
    }
}

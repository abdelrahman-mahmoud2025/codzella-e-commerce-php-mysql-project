<?php
// 👤 User Model - نموذج المستخدمين
// بيتعامل مع جدول users في قاعدة البيانات

class User {
    
    // متغير الاتصال بقاعدة البيانات
    private $db;
    // اسم الجدول
    private $table = 'users';
    
    // Constructor - بيشتغل أول ما نعمل كائن من الكلاس
    public function __construct() {
        // جيب الاتصال بقاعدة البيانات
        $this->db = Database::getInstance()->getConnection();
    }
    
    // 🔍 البحث عن مستخدم بالـ ID
    public function findById($id) {
        // جهز الاستعلام (Prepared Statement للحماية)
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE id = ?");
        // نفذ الاستعلام
        $stmt->execute([$id]);
        // ارجع بيانات المستخدم
        return $stmt->fetch();
    }
    
    // 🔍 البحث عن مستخدم بالبريد الإلكتروني
    public function findByEmail($email) {
        // جهز الاستعلام
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE email = ?");
        // نفذ الاستعلام
        $stmt->execute([$email]);
        // ارجع بيانات المستخدم
        return $stmt->fetch();
    }
    
    // 🔍 البحث عن مستخدم باسم المستخدم
    public function findByUsername($username) {
        // جهز الاستعلام
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE username = ?");
        // نفذ الاستعلام
        $stmt->execute([$username]);
        // ارجع بيانات المستخدم
        return $stmt->fetch();
    }
    
    // 📝 تسجيل مستخدم جديد
    public function register($data) {
        try {
            // شفر الباسورد (Hash) عشان منحفظهوش واضح في قاعدة البيانات
            $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);
            
            // جهز استعلام الإضافة
            $stmt = $this->db->prepare("
                INSERT INTO {$this->table} (username, email, password, full_name, phone, address) 
                VALUES (?, ?, ?, ?, ?, ?)
            ");
            
            // نفذ الاستعلام بالبيانات
            $stmt->execute([
                $data['username'],  // اسم المستخدم
                $data['email'],  // البريد الإلكتروني
                $hashedPassword,  // الباسورد المشفر
                $data['full_name'],  // الاسم الكامل
                $data['phone'] ?? null,  // الهاتف (اختياري)
                $data['address'] ?? null  // العنوان (اختياري)
            ]);
            
            // ارجع ID المستخدم الجديد
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            // لو حصل خطأ، سجله
            error_log("تسجيل خطأ: " . $e->getMessage());
            return false;
        }
    }
    
    // 🔑 تسجيل الدخول
    public function login($email, $password) {
        // دور على المستخدم بالبريد
        $user = $this->findByEmail($email);
        
        // لو المستخدم موجود والباسورد صح
        if ($user && password_verify($password, $user['password'])) {
            // تحقق إن الحساب نشط (is_active = 1)
            if ($user['is_active']) {
                // ارجع بيانات المستخدم
                return $user;
            }
        }
        
        // لو البريد أو الباسورد غلط، ارجع false
        return false;
    }
    
    // 🔄 تحديث بيانات مستخدم
    public function update($id, $data) {
        try {
            // arrays للحقول والقيم
            $fields = [];
            $values = [];
            
            // لكل حقل في البيانات
            foreach ($data as $key => $value) {
                // لو مش ID ولا password (منحدثهمش من هنا)
                if ($key !== 'id' && $key !== 'password') {
                    // أضف الحقل للاستعلام
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
            error_log("تحديث خطأ: " . $e->getMessage());
            return false;
        }
    }
    
    // 🔐 تحديث كلمة المرور
    public function updatePassword($id, $newPassword) {
        try {
            // شفر الباسورد الجديد
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            // حدث الباسورد في قاعدة البيانات
            $stmt = $this->db->prepare("UPDATE {$this->table} SET password = ? WHERE id = ?");
            return $stmt->execute([$hashedPassword, $id]);
        } catch (PDOException $e) {
            // لو حصل خطأ، سجله
            error_log("تحديث باسورد خطأ: " . $e->getMessage());
            return false;
        }
    }
    
    // 📋 جيب كل المستخدمين (للأدمن)
    public function getAll($limit = null, $offset = 0) {
        // استعلام SELECT (مش بنجيب الباسورد عشان الأمان)
        $sql = "SELECT id, username, email, full_name, phone, role, is_active, created_at 
                FROM {$this->table} ORDER BY created_at DESC";
        
        // لو في limit (عدد محدد)، أضفه
        if ($limit) {
            $sql .= " LIMIT {$limit} OFFSET {$offset}";
        }
        
        // نفذ الاستعلام
        $stmt = $this->db->query($sql);
        // ارجع كل النتائج
        return $stmt->fetchAll();
    }
    
    // 🔢 احسب عدد المستخدمين
    public function count() {
        // نفذ استعلام COUNT
        $stmt = $this->db->query("SELECT COUNT(*) FROM {$this->table}");
        // ارجع العدد
        return $stmt->fetchColumn();
    }
    
    // ❌ حذف مستخدم
    public function delete($id) {
        try {
            // جهز استعلام الحذف
            $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = ?");
            // نفذ الاستعلام
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            // لو حصل خطأ، سجله
            error_log("حذف خطأ: " . $e->getMessage());
            return false;
        }
    }
}

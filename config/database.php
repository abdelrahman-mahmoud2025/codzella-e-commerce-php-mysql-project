<?php
/**
 * ملف إعدادات قاعدة البيانات
 * 
 * ده الكلاس المسؤول عن التعامل مع قاعدة البيانات باستخدام PDO
 * بنستخدم Singleton Pattern عشان نضمن إنه مفيش غير اتصال واحد بالداتابيز
 **/
class Database {
    // المتغير اللي هيتخزن فيه نسخة الكلاس (جزء من Singleton Pattern)
    private static $instance = null;
    
    // المتغير اللي هيتخزن فيه الاتصال بالداتابيز
    private $connection;

    // Constructor خاص (private) عشان منتعملش كائن من الكلاس من بره
    private function __construct() {
        try {
            // بنبني الـ DSN (Data Source Name) عشان نعرف نتواصل مع الداتابيز
            // DSN بيحتوي على: نوع الداتابيز + السيرفر + اسم الداتابيز + الترميز
            // بنبني الـ DSN من الثوابت الموجودة في ملف env.php
            // مثال: "mysql:host=localhost;dbname=store;charset=utf8mb4"
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
            // إعدادات إضافية للاتصال بالداتابيز
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // نخلي الأخطاء تظهر كـ exceptions
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // البيانات تيجي كـ associative array
                PDO::ATTR_EMULATE_PREPARES => false, // نستخدم prepared statements الحقيقية
            ];
            
            // بنعمل اتصال جديد بالداتابيز باستخدام البيانات من ملف env.php
            $this->connection = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            // لو حصل خطأ في الاتصال، نوقف السكربط ونعرض رسالة الخطأ
            die("فشل الاتصال بقاعدة البيانات: " . $e->getMessage());
        }
    }

    // دالة static عشان نجيب نسخة من الكلاس (جزء من Singleton Pattern)
    // دي الطريقة الوحيدة اللي نقدّر نجيب بيها الكائن
    public static function getInstance() {
        // لو مفيش نسخة من الكلاس اتعملت قبل كده
        if (self::$instance === null) {
            // نعمل نسخة جديدة
            self::$instance = new self();
        }
        // نرجع النسخة الموجودة
        return self::$instance;
    }

    // دالة نجيب بيها الاتصال بالداتابيز
    public function getConnection() {
        return $this->connection;
    }

    // منع نسخ الكائن (جزء من Singleton Pattern)
    private function __clone() {}

    // منع إعادة إنشاء الكائن من serialize/unserialize
    public function __wakeup() {
        throw new Exception("مش ممكن تعمل unserialize للكائن ده");
    }
}

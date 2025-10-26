<?php
// 🏠 HomeController - المتحكم الرئيسي للصفحات العامة
// ده الكلاس الأساسي اللي كل الـ Controllers التانية بترث منه

class HomeController {
    
    // 📄 دالة الصفحة الرئيسية
    public function index() {
        // إنشاء كائن من Product Model عشان نجيب المنتجات
        $productModel = new Product();
        // إنشاء كائن من Category Model عشان نجيب التصنيفات
        $categoryModel = new Category();
        
        // جيب 8 منتجات مميزة (Featured)
        $featuredProducts = $productModel->getFeatured(8);
        // جيب آخر 12 منتج تم إضافتهم
        $latestProducts = $productModel->getLatest(12);
        // جيب كل التصنيفات النشطة (true = النشطة بس)
        $categories = $categoryModel->getAll(true);
        
        // جهز البيانات اللي هتروح للـ View
        $data = [
            'title' => 'Home',  // عنوان الصفحة
            'featuredProducts' => $featuredProducts,  // المنتجات المميزة
            'latestProducts' => $latestProducts,  // أحدث المنتجات
            'categories' => $categories  // التصنيفات
        ];
        
        // اعرض صفحة home/index.php وابعتلها البيانات
        $this->view('home/index', $data);
    }
    
    // 📖 صفحة من نحن
    public function about() {
        // جهز بيانات بسيطة (عنوان الصفحة بس)
        $data = ['title' => 'About Us'];
        // اعرض صفحة home/about.php
        $this->view('home/about', $data);
    }
    
    // 📞 صفحة اتصل بنا
    public function contact() {
        // جهز بيانات بسيطة
        $data = ['title' => 'Contact Us'];
        // اعرض صفحة home/contact.php
        $this->view('home/contact', $data);
    }
    
    // ❌ صفحة 404 (الصفحة مش موجودة)
    public function notFound() {
        // قول للمتصفح إن الصفحة مش موجودة (كود 404)
        http_response_code(404);
        // جهز البيانات
        $data = ['title' => '404 - Page Not Found'];
        // اعرض صفحة الخطأ errors/404.php
        $this->view('errors/404', $data);
    }
    
    // 🎨 دالة عرض الصفحات (View)
    // ده اللي بيجمع header + الصفحة + footer
    protected function view($view, $data = []) {
        // حول الـ Array لمتغيرات عادية
        // مثلاً: ['title' => 'Home'] يبقى $title = 'Home'
        extract($data);
        
        // حدد مكان ملف الـ View
        // مثلاً: home/index يبقى views/home/index.php
        $viewFile = __DIR__ . '/../../views/' . $view . '.php';
        
        // تحقق إن الملف موجود
        if (file_exists($viewFile)) {
            // اعرض الـ Header (شريط التنقل العلوي)
            require_once __DIR__ . '/../../views/layouts/header.php';
            // اعرض محتوى الصفحة نفسها
            require_once $viewFile;
            // اعرض الـ Footer (ذيل الصفحة)
            require_once __DIR__ . '/../../views/layouts/footer.php';
        } else {
            // لو الملف مش موجود، اطبع رسالة خطأ
            die("View not found: {$view}");
        }
    }
    
    // 🔄 دالة إعادة التوجيه (Redirect)
    // بتاخد المستخدم لصفحة تانية
    protected function redirect($url) {
        // حول المستخدم للصفحة المطلوبة
        // مثلاً: redirect('/login') يروح لصفحة تسجيل الدخول
        header("Location: " . APP_URL . $url);
        // وقف تنفيذ أي كود بعد كده
        exit;
    }
    
    // 📊 دالة إرجاع JSON (للـ AJAX)
    // بترجع بيانات بصيغة JSON بدل HTML
    protected function json($data, $statusCode = 200) {
        // حدد كود الاستجابة (200 = نجح، 404 = مش موجود، إلخ)
        http_response_code($statusCode);
        // قول للمتصفح إن الاستجابة JSON
        header('Content-Type: application/json');
        // حول البيانات لـ JSON واطبعها
        echo json_encode($data);
        // وقف التنفيذ
        exit;
    }
}

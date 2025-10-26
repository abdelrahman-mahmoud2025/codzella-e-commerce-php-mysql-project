<?php
// 🛍️ ProductController - المتحكم الخاص بعرض المنتجات للعملاء
// بيرث من HomeController عشان يستخدم دوال view و redirect

class ProductController extends HomeController {
    
    // متغير خاص بـ Product Model (للتعامل مع جدول المنتجات)
    private $productModel;
    // متغير خاص بـ Category Model (للتعامل مع جدول التصنيفات)
    private $categoryModel;
    
    // Constructor - بيشتغل أول ما نعمل كائن من الكلاس
    public function __construct() {
        // إنشاء كائن من Product Model
        $this->productModel = new Product();
        // إنشاء كائن من Category Model
        $this->categoryModel = new Category();
    }
    
    // 📋 دالة عرض قائمة المنتجات (مع بحث وفلترة وترقيم صفحات)
    public function index() {
        // جيب رقم الصفحة الحالية من الـ URL (لو مش موجود، يبقى صفحة 1)
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        // عدد المنتجات في كل صفحة (12 منتج)
        $perPage = 12;
        // احسب من فين نبدأ (مثلاً صفحة 2 = نبدأ من منتج رقم 13)
        $offset = ($page - 1) * $perPage;
        
        // الفلاتر الأساسية (نعرض المنتجات النشطة بس)
        $filters = ['is_active' => 1];
        
        // لو المستخدم اختار تصنيف معين
        if (isset($_GET['category'])) {
            // أضف التصنيف للفلاتر
            $filters['category_id'] = (int)$_GET['category'];
        }
        
        // لو المستخدم بيبحث عن حاجة
        if (isset($_GET['search'])) {
            // أضف كلمة البحث للفلاتر
            $filters['search'] = $_GET['search'];
        }
        
        // جيب المنتجات حسب الفلاتر (12 منتج بس في الصفحة)
        $products = $this->productModel->getAll($filters, $perPage, $offset);
        // احسب إجمالي عدد المنتجات (لحساب عدد الصفحات)
        $totalProducts = $this->productModel->count($filters);
        // احسب عدد الصفحات الكلي (مثلاً 50 منتج ÷ 12 = 5 صفحات)
        $totalPages = ceil($totalProducts / $perPage);
        
        // جيب كل التصنيفات النشطة (عشان نعرضها في الفلتر)
        $categories = $this->categoryModel->getAll(true);
        
        // جهز البيانات اللي هتروح للـ View
        $data = [
            'title' => 'Products',  // عنوان الصفحة
            'products' => $products,  // المنتجات
            'categories' => $categories,  // التصنيفات
            'currentPage' => $page,  // الصفحة الحالية
            'totalPages' => $totalPages  // إجمالي الصفحات
        ];
        
        // اعرض صفحة قائمة المنتجات
        $this->view('products/index', $data);
    }
    
    // 🔍 دالة عرض تفاصيل منتج واحد
    public function show($slug) {
        // جيب المنتج من قاعدة البيانات باستخدام الـ slug
        // مثلاً: /products/show/laptop-dell-xps
        $product = $this->productModel->findBySlug($slug);
        
        // لو المنتج مش موجود أو مش نشط
        if (!$product || !$product['is_active']) {
            // اعرض صفحة 404
            $this->notFound();
            return;
        }
        
        // جيب منتجات مشابهة من نفس التصنيف (4 منتجات بس)
        $relatedProducts = $this->productModel->getAll([
            'category_id' => $product['category_id'],  // نفس التصنيف
            'is_active' => 1  // النشطة بس
        ], 4);  // 4 منتجات فقط
        
        // جهز البيانات
        $data = [
            'title' => $product['name'],  // عنوان الصفحة = اسم المنتج
            'product' => $product,  // بيانات المنتج
            'relatedProducts' => $relatedProducts  // المنتجات المشابهة
        ];
        
        // اعرض صفحة تفاصيل المنتج
        $this->view('products/show', $data);
    }
    
    // 🏷️ دالة عرض منتجات تصنيف معين
    public function category($categorySlug) {
        // جيب التصنيف من قاعدة البيانات باستخدام الـ slug
        // مثلاً: /products/category/electronics
        $category = $this->categoryModel->findBySlug($categorySlug);
        
        // لو التصنيف مش موجود
        if (!$category) {
            // اعرض صفحة 404
            $this->notFound();
            return;
        }
        
        // جيب رقم الصفحة الحالية
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        // عدد المنتجات في كل صفحة
        $perPage = 12;
        // احسب من فين نبدأ
        $offset = ($page - 1) * $perPage;
        
        // جيب منتجات التصنيف ده بس
        $products = $this->productModel->getAll([
            'category_id' => $category['id'],  // التصنيف المحدد
            'is_active' => 1  // النشطة بس
        ], $perPage, $offset);
        
        // احسب إجمالي المنتجات في التصنيف ده
        $totalProducts = $this->productModel->count(['category_id' => $category['id']]);
        // احسب عدد الصفحات
        $totalPages = ceil($totalProducts / $perPage);
        
        // جهز البيانات
        $data = [
            'title' => $category['name'],  // عنوان الصفحة = اسم التصنيف
            'category' => $category,  // بيانات التصنيف
            'products' => $products,  // المنتجات
            'currentPage' => $page,  // الصفحة الحالية
            'totalPages' => $totalPages  // إجمالي الصفحات
        ];
        
        // اعرض صفحة منتجات التصنيف
        $this->view('products/category', $data);
    }
}

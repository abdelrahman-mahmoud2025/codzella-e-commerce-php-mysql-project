<?php
// 👑 AdminController - لوحة التحكم الإدارية (أكبر Controller!)
// بيرث من HomeController - بس بحماية: لازم يكون Admin

class AdminController extends HomeController {
    
    // Models اللي هنستخدمها
    private $productModel;
    private $categoryModel;
    private $orderModel;
    private $userModel;
    private $validator;
    
    // Constructor - بيشتغل قبل أي دالة (حماية!)
    public function __construct() {
        // 🛡️ تحقق: هل المستخدم أدمن؟
        if (!SessionHelper::isAdmin()) {
            // لو مش أدمن، ارفض الدخول
            SessionHelper::setFlash('error', 'ممنوع الدخول. لازم تكون أدمن!');
            header("Location: " . APP_URL . '/');
            exit;
        }
        
        // إنشاء كائنات الـ Models
        $this->productModel = new Product();
        $this->categoryModel = new Category();
        $this->orderModel = new Order();
        $this->userModel = new User();
        $this->validator = new ValidationHelper();
    }
    
    // 📊 لوحة التحكم الرئيسية (Dashboard)
    public function dashboard() {
        // احسب الإحصائيات
        $stats = [
            'total_products' => $this->productModel->count(),  // عدد المنتجات
            'total_orders' => $this->orderModel->count(),  // عدد الطلبات
            'total_users' => $this->userModel->count(),  // عدد المستخدمين
            'total_revenue' => $this->orderModel->getTotalRevenue(),  // إجمالي الإيرادات
            'pending_orders' => $this->orderModel->count(['status' => 'pending'])  // الطلبات المعلقة
        ];
        
        // جيب آخر 10 طلبات
        $recentOrders = $this->orderModel->getAll([], 10);
        
        // جهز البيانات
        $data = [
            'title' => 'Admin Dashboard',
            'stats' => $stats,
            'recentOrders' => $recentOrders
        ];
        
        // اعرض صفحة Dashboard
        $this->view('admin/dashboard', $data);
    }
    
    // 🛍️ إدارة المنتجات - قائمة المنتجات
    public function products() {
        // جيب كل المنتجات من قاعدة البيانات
        $products = $this->productModel->getAll();
        
        // جهز البيانات اللي هتروح للـ View
        $data = [
            'title' => 'Manage Products',  // عنوان الصفحة
            'products' => $products  // قائمة المنتجات
        ];
        
        // اعرض صفحة قائمة المنتجات (admin/products/index.php)
        $this->view('admin/products/index', $data);
    }
    
    // ➕ إضافة منتج جديد
    public function createProduct() {
        // لو الأدمن عامل Submit للفورم (ضغط على زر Create)
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // تحقق من CSRF Token (حماية من الهجمات)
            if (!SessionHelper::verifyCsrfToken($_POST['csrf_token'] ?? '')) {
                SessionHelper::setFlash('error', 'طلب غير صالح');
                $this->redirect('/admin/products');
            }
            
            // نضف كل البيانات اللي جت من الفورم
            $data = ValidationHelper::sanitize($_POST);
            
            // احذف csrf_token من البيانات (مش محتاجينه في القاعدة)
            unset($data['csrf_token']);
            
            // ولد slug من اسم المنتج (مثلاً: "Laptop Dell" يبقى "laptop-dell")
            $data['slug'] = $this->productModel->generateSlug($data['name']);
            
            // التعامل مع الـ Checkboxes (is_featured و is_active)
            // الـ Checkbox لو مش محدد، مبيبعتش قيمة في POST
            $data['is_featured'] = isset($_POST['is_featured']) ? 1 : 0;  // منتج مميز؟
            $data['is_active'] = isset($_POST['is_active']) ? 1 : 0;  // منتج نشط؟
            
            // التعامل مع رفع الصورة
            // تحقق إن في صورة اترفعت وإن مفيش أخطاء
            if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
                // ارفع الصورة باستخدام دالة uploadImage
                $uploadedImage = $this->uploadImage($_FILES['image']);
                // لو الرفع نجح، احفظ اسم الصورة
                if ($uploadedImage) {
                    $data['image'] = $uploadedImage;
                }
            }
            
            // حاول تضيف المنتج في قاعدة البيانات
            if ($this->productModel->create($data)) {
                // نجح! اعرض رسالة نجاح
                SessionHelper::setFlash('success', 'تم إضافة المنتج بنجاح');
                // روح لصفحة قائمة المنتجات
                $this->redirect('/admin/products');
            } else {
                // فشل! اعرض رسالة خطأ
                SessionHelper::setFlash('error', 'فشل إضافة المنتج. تحقق من كل الحقول.');
            }
        }
        
        // لو مش POST (يعني GET)، اعرض فورم الإضافة
        // جيب كل التصنيفات (false = حتى الغير نشطة)
        $categories = $this->categoryModel->getAll(false);
        
        // جهز البيانات للفورم
        $data = [
            'title' => 'Create Product',  // عنوان الصفحة
            'categories' => $categories,  // قائمة التصنيفات
            'csrf_token' => SessionHelper::generateCsrfToken()  // CSRF Token
        ];
        
        // اعرض صفحة فورم إضافة منتج
        $this->view('admin/products/create', $data);
    }
    
    // ✏️ تعديل منتج موجود
    public function editProduct($id) {
        // جيب بيانات المنتج من القاعدة
        $product = $this->productModel->findById($id);
        
        // لو المنتج مش موجود
        if (!$product) {
            // اعرض صفحة 404
            $this->notFound();
            return;
        }
        
        // لو الأدمن عامل Submit (ضغط على زر Update)
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // تحقق من CSRF Token
            if (!SessionHelper::verifyCsrfToken($_POST['csrf_token'] ?? '')) {
                SessionHelper::setFlash('error', 'طلب غير صالح');
                $this->redirect('/admin/products');
            }
            
            // نضف البيانات الجديدة
            $data = ValidationHelper::sanitize($_POST);
            
            // احذف csrf_token من البيانات
            unset($data['csrf_token']);
            
            // التعامل مع الـ Checkboxes
            // (الـ Checkbox لو مش محدد، مبيبعتش قيمة)
            $data['is_featured'] = isset($_POST['is_featured']) ? 1 : 0;
            $data['is_active'] = isset($_POST['is_active']) ? 1 : 0;
            
            // التعامل مع رفع صورة جديدة
            if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
                // في صورة جديدة، ارفعها
                $uploadedImage = $this->uploadImage($_FILES['image']);
                if ($uploadedImage) {
                    $data['image'] = $uploadedImage;
                }
            } else {
                // مفيش صورة جديدة، خلي الصورة القديمة زي ما هي
                // احذف image من البيانات عشان منحدثهاش
                unset($data['image']);
            }
            
            // حدث المنتج في القاعدة
            $result = $this->productModel->update($id, $data);
            
            // لو التحديث نجح
            if ($result) {
                SessionHelper::setFlash('success', 'تم تحديث المنتج بنجاح');
                $this->redirect('/admin/products');
            } else {
                // لو فشل
                SessionHelper::setFlash('error', 'فشل تحديث المنتج. تحقق من كل الحقول.');
            }
        }
        
        // لو مش POST (يعني GET)، اعرض فورم التعديل
        // جيب كل التصنيفات
        $categories = $this->categoryModel->getAll(false);
        
        // جهز البيانات للفورم
        $data = [
            'title' => 'Edit Product',  // عنوان الصفحة
            'product' => $product,  // بيانات المنتج الحالية
            'categories' => $categories,  // قائمة التصنيفات
            'csrf_token' => SessionHelper::generateCsrfToken()  // CSRF Token
        ];
        
        // اعرض صفحة فورم التعديل (مملوء بالبيانات الحالية)
        $this->view('admin/products/edit', $data);
    }
    
    // ❌ حذف منتج
    public function deleteProduct($id) {
        // حاول تحذف المنتج
        if ($this->productModel->delete($id)) {
            // نجح
            SessionHelper::setFlash('success', 'تم حذف المنتج بنجاح');
        } else {
            // فشل
            SessionHelper::setFlash('error', 'فشل حذف المنتج');
        }
        
        // روح لصفحة قائمة المنتجات
        $this->redirect('/admin/products');
    }
    
    // 🏷️ إدارة التصنيفات - قائمة التصنيفات
    public function categories() {
        // جيب كل التصنيفات (false = حتى الغير نشطة)
        $categories = $this->categoryModel->getAll(false);
        
        // جهز البيانات
        $data = [
            'title' => 'Manage Categories',
            'categories' => $categories
        ];
        
        // اعرض صفحة قائمة التصنيفات
        $this->view('admin/categories/index', $data);
    }
    
    // ➕ إضافة تصنيف جديد
    public function createCategory() {
        // لو الأدمن عامل Submit
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // تحقق من CSRF Token
            if (!SessionHelper::verifyCsrfToken($_POST['csrf_token'] ?? '')) {
                SessionHelper::setFlash('error', 'طلب غير صالح');
                $this->redirect('/admin/categories');
            }
            
            // نضف البيانات
            $data = ValidationHelper::sanitize($_POST);
            
            // احذف csrf_token
            unset($data['csrf_token']);
            
            // ولد slug من اسم التصنيف
            $data['slug'] = $this->categoryModel->generateSlug($data['name']);
            // التعامل مع checkbox النشاط
            $data['is_active'] = isset($_POST['is_active']) ? 1 : 0;
            
            // حاول تضيف التصنيف
            if ($this->categoryModel->create($data)) {
                SessionHelper::setFlash('success', 'تم إضافة التصنيف بنجاح');
                $this->redirect('/admin/categories');
            } else {
                SessionHelper::setFlash('error', 'فشل إضافة التصنيف');
            }
        }
        
        // لو GET، اعرض الفورم
        $data = [
            'title' => 'Create Category',
            'csrf_token' => SessionHelper::generateCsrfToken()
        ];
        
        $this->view('admin/categories/create', $data);
    }
    
    // ✏️ تعديل تصنيف
    public function editCategory($id) {
        // جيب بيانات التصنيف
        $category = $this->categoryModel->findById($id);
        
        // لو التصنيف مش موجود
        if (!$category) {
            $this->notFound();
            return;
        }
        
        // لو الأدمن عامل Submit
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // تحقق من CSRF Token
            if (!SessionHelper::verifyCsrfToken($_POST['csrf_token'] ?? '')) {
                SessionHelper::setFlash('error', 'طلب غير صالح');
                $this->redirect('/admin/categories');
            }
            
            // نضف البيانات الجديدة
            $data = ValidationHelper::sanitize($_POST);
            
            // احذف csrf_token
            unset($data['csrf_token']);
            
            // التعامل مع checkbox النشاط
            $data['is_active'] = isset($_POST['is_active']) ? 1 : 0;
            
            // حدث التصنيف
            if ($this->categoryModel->update($id, $data)) {
                SessionHelper::setFlash('success', 'تم تحديث التصنيف بنجاح');
                $this->redirect('/admin/categories');
            } else {
                SessionHelper::setFlash('error', 'فشل تحديث التصنيف');
            }
        }
        
        // لو GET، اعرض فورم التعديل
        $data = [
            'title' => 'Edit Category',
            'category' => $category,
            'csrf_token' => SessionHelper::generateCsrfToken()
        ];
        
        $this->view('admin/categories/edit', $data);
    }
    
    // ❌ حذف تصنيف
    public function deleteCategory($id) {
        // حاول تحذف التصنيف
        if ($this->categoryModel->delete($id)) {
            SessionHelper::setFlash('success', 'تم حذف التصنيف بنجاح');
        } else {
            // لو فشل (ممكن يكون فيه منتجات مربوطة بيه)
            SessionHelper::setFlash('error', 'فشل حذف التصنيف. قد يحتوي على منتجات.');
        }
        
        // روح لصفحة قائمة التصنيفات
        $this->redirect('/admin/categories');
    }
    
    // 📦 إدارة الطلبات - قائمة الطلبات
    public function orders() {
        // جيب كل الطلبات
        $orders = $this->orderModel->getAll();
        
        // جهز البيانات
        $data = [
            'title' => 'Manage Orders',
            'orders' => $orders
        ];
        
        // اعرض صفحة قائمة الطلبات
        $this->view('admin/orders/index', $data);
    }
    
    // 👁️ عرض تفاصيل طلب معين
    public function viewOrder($id) {
        // جيب بيانات الطلب
        $order = $this->orderModel->findById($id);
        
        // لو الطلب مش موجود
        if (!$order) {
            $this->notFound();
            return;
        }
        
        // جيب منتجات الطلب
        $orderItems = $this->orderModel->getOrderItems($id);
        
        // جهز البيانات
        $data = [
            'title' => 'Order Details',
            'order' => $order,  // بيانات الطلب
            'orderItems' => $orderItems  // المنتجات
        ];
        
        // اعرض صفحة تفاصيل الطلب
        $this->view('admin/orders/view', $data);
    }
    
    // 🔄 تحديث حالة الطلب
    public function updateOrderStatus($id) {
        // لو الأدمن عامل Submit
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // جيب الحالة الجديدة من الفورم
            $status = $_POST['status'] ?? '';
            
            // حدث حالة الطلب (pending → processing → completed)
            if ($this->orderModel->updateStatus($id, $status)) {
                SessionHelper::setFlash('success', 'تم تحديث حالة الطلب');
            } else {
                SessionHelper::setFlash('error', 'فشل تحديث حالة الطلب');
            }
        }
        
        // روح لصفحة قائمة الطلبات
        $this->redirect('/admin/orders');
    }
    
    // 📸 دالة مساعدة لرفع الصور (Private - للاستخدام الداخلي فقط)
    private function uploadImage($file) {
        // أنواع الصور المسموح بها
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        // الحجم الأقصى للصورة (من config.php)
        $maxSize = MAX_FILE_SIZE;
        
        // تحقق: هل نوع الملف مسموح؟
        if (!in_array($file['type'], $allowedTypes)) {
            SessionHelper::setFlash('error', 'نوع الملف غير مسموح');
            return null;  // ارجع null (فشل)
        }
        
        // تحقق: هل حجم الملف أكبر من المسموح؟
        if ($file['size'] > $maxSize) {
            SessionHelper::setFlash('error', 'حجم الملف كبير جداً');
            return null;  // ارجع null (فشل)
        }
        
        // جيب امتداد الملف (مثلاً: jpg, png)
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        // ولد اسم فريد للصورة (مثلاً: 6789abc123.jpg)
        $filename = uniqid() . '.' . $extension;
        // المسار الكامل للصورة (مثلاً: public/uploads/6789abc123.jpg)
        $destination = UPLOAD_DIR . $filename;
        
        // حاول تنقل الصورة من المجلد المؤقت للمجلد النهائي
        if (move_uploaded_file($file['tmp_name'], $destination)) {
            // نجح! ارجع اسم الملف
            return $filename;
        }
        
        // فشل الرفع
        return null;
    }
}

<?php
// 🔐 AuthController - المتحكم الخاص بالمصادقة (تسجيل دخول/تسجيل/خروج)
// بيرث من HomeController عشان يستخدم دوال view و redirect

class AuthController extends HomeController {
    
    // متغير خاص بـ User Model (للتعامل مع جدول المستخدمين)
    private $userModel;
    // متغير خاص بـ Validator (للتحقق من البيانات)
    private $validator;
    
    // Constructor - بيشتغل أول ما نعمل كائن من الكلاس
    public function __construct() {
        // إنشاء كائن من User Model
        $this->userModel = new User();
        // إنشاء كائن من ValidationHelper
        $this->validator = new ValidationHelper();
    }
    
    // 🔑 دالة تسجيل الدخول
    public function login() {
        // لو المستخدم مسجل دخول خلاص، روحه للصفحة الرئيسية
        if (SessionHelper::isLoggedIn()) {
            $this->redirect('/');
        }
        
        // لو المستخدم عامل Submit للفورم (ضغط على زر Login)
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // جيب البريد الإلكتروني ونضفه من أي حاجة خطيرة
            $email = ValidationHelper::sanitizeEmail($_POST['email'] ?? '');
            // جيب الباسورد (مش بننضفه عشان ممكن يكون فيه رموز خاصة)
            $password = $_POST['password'] ?? '';
            
            // تحقق من CSRF Token (حماية من الهجمات)
            // لو الـ Token غلط، يبقى حد بيحاول يهاجم الموقع
            if (!SessionHelper::verifyCsrfToken($_POST['csrf_token'] ?? '')) {
                SessionHelper::setFlash('error', 'Invalid request');
                $this->redirect('/login');
            }
            
            // حاول تسجل الدخول عن طريق User Model
            // بيتحقق من البريد والباسورد في قاعدة البيانات
            $user = $this->userModel->login($email, $password);
            
            // لو تسجيل الدخول نجح (المستخدم موجود والباسورد صح)
            if ($user) {
                // احفظ بيانات المستخدم في الـ Session
                SessionHelper::setUser($user);
                // اعرض رسالة ترحيب
                SessionHelper::setFlash('success', 'أهلاً بيك يا ' . $user['full_name']);
                
                // حول المستخدم حسب نوعه (أدمن ولا عميل عادي)
                if ($user['role'] === 'admin') {
                    // لو أدمن، روح للوحة التحكم
                    $this->redirect('/admin/dashboard');
                } else {
                    // لو عميل عادي، روح للصفحة الرئيسية
                    $this->redirect('/');
                }
            } else {
                // لو تسجيل الدخول فشل (البريد أو الباسورد غلط)
                SessionHelper::setFlash('error', 'البريد أو الباسورد غلط');
                $this->redirect('/login');
            }
        }
        
        // جهز البيانات اللي هتروح لصفحة تسجيل الدخول
        $data = [
            'title' => 'Login',  // عنوان الصفحة
            'csrf_token' => SessionHelper::generateCsrfToken()  // توليد CSRF Token جديد
        ];
        
        // اعرض صفحة تسجيل الدخول
        $this->view('auth/login', $data);
    }
    
    // 📝 دالة التسجيل (إنشاء حساب جديد)
    public function register() {
        // لو المستخدم مسجل دخول خلاص، روحه للصفحة الرئيسية
        if (SessionHelper::isLoggedIn()) {
            $this->redirect('/');
        }
        
        // لو المستخدم عامل Submit للفورم (ضغط على زر Register)
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // تحقق من CSRF Token (حماية)
            if (!SessionHelper::verifyCsrfToken($_POST['csrf_token'] ?? '')) {
                SessionHelper::setFlash('error', 'Invalid request');
                $this->redirect('/register');
            }
            
            // نضف كل البيانات اللي جت من الفورم
            $data = ValidationHelper::sanitize($_POST);
            
            // قواعد التحقق من البيانات
            $rules = [
                'username' => 'required|min:3|max:50|unique:users,username',  // اسم المستخدم لازم يكون فريد
                'email' => 'required|email|unique:users,email',  // البريد لازم يكون صحيح وفريد
                'password' => 'required|min:' . PASSWORD_MIN_LENGTH,  // الباسورد لازم على الأقل 8 حروف
                'confirm_password' => 'required|match:password',  // تأكيد الباسورد لازم يطابق الباسورد
                'full_name' => 'required|min:3|max:100'  // الاسم الكامل مطلوب
            ];
            
            // تحقق من البيانات حسب القواعد
            if ($this->validator->validate($data, $rules)) {
                // كل حاجة تمام، سجل المستخدم في قاعدة البيانات
                $userId = $this->userModel->register($data);
                
                // لو التسجيل نجح
                if ($userId) {
                    // جيب بيانات المستخدم اللي اتسجل
                    $user = $this->userModel->findById($userId);
                    // سجله دخول تلقائي
                    SessionHelper::setUser($user);
                    // اعرض رسالة ترحيب
                    SessionHelper::setFlash('success', 'تم التسجيل بنجاح! أهلاً بيك في CodeZilla Store');
                    // روح للصفحة الرئيسية
                    $this->redirect('/');
                } else {
                    // لو التسجيل فشل (مشكلة في قاعدة البيانات)
                    SessionHelper::setFlash('error', 'فيه مشكلة في التسجيل. حاول تاني');
                    $this->redirect('/register');
                }
            } else {
                // لو في أخطاء في البيانات (مثلاً البريد مكرر)
                // اعرض أول خطأ
                SessionHelper::setFlash('error', $this->validator->getFirstError());
                $this->redirect('/register');
            }
        }
        
        // جهز البيانات لصفحة التسجيل
        $data = [
            'title' => 'Register',
            'csrf_token' => SessionHelper::generateCsrfToken()
        ];
        
        // اعرض صفحة التسجيل
        $this->view('auth/register', $data);
    }
    
    // 🚪 دالة تسجيل الخروج
    public function logout() {
        // امسح بيانات المستخدم من الـ Session
        SessionHelper::logout();
        // اعرض رسالة نجاح
        SessionHelper::setFlash('success', 'تم تسجيل الخروج بنجاح');
        // روح للصفحة الرئيسية
        $this->redirect('/');
    }
    
    // 👤 دالة الملف الشخصي (Profile)
    public function profile() {
        // لو المستخدم مش مسجل دخول، روحه لصفحة تسجيل الدخول
        if (!SessionHelper::isLoggedIn()) {
            $this->redirect('/login');
        }
        
        // جيب ID المستخدم من الـ Session
        $userId = SessionHelper::getUserId();
        // جيب بيانات المستخدم من قاعدة البيانات
        $user = $this->userModel->findById($userId);
        
        // لو المستخدم عامل Submit (بيحدث بياناته)
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // تحقق من CSRF Token
            if (!SessionHelper::verifyCsrfToken($_POST['csrf_token'] ?? '')) {
                SessionHelper::setFlash('error', 'Invalid request');
                $this->redirect('/profile');
            }
            
            // نضف البيانات الجديدة
            $data = ValidationHelper::sanitize($_POST);
            
            // حدث بيانات المستخدم في قاعدة البيانات
            if ($this->userModel->update($userId, $data)) {
                // لو التحديث نجح
                SessionHelper::setFlash('success', 'تم تحديث البيانات بنجاح');
            } else {
                // لو التحديث فشل
                SessionHelper::setFlash('error', 'فشل تحديث البيانات');
            }
            
            // ارجع لصفحة الملف الشخصي
            $this->redirect('/profile');
        }
        
        // جهز البيانات لصفحة الملف الشخصي
        $data = [
            'title' => 'My Profile',
            'user' => $user,  // بيانات المستخدم الحالية
            'csrf_token' => SessionHelper::generateCsrfToken()
        ];
        
        // اعرض صفحة الملف الشخصي
        $this->view('auth/profile', $data);
    }
}

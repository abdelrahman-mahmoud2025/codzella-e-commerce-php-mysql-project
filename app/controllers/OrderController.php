<?php
// 📦 OrderController - المتحكم الخاص بالطلبات والدفع
// بيرث من HomeController عشان يستخدم دوال view و redirect

class OrderController extends HomeController {
    
    // متغير خاص بـ Order Model (للتعامل مع جدول الطلبات)
    private $orderModel;
    // متغير خاص بـ Validator (للتحقق من البيانات)
    private $validator;
    
    // Constructor - بيشتغل أول ما نعمل كائن من الكلاس
    public function __construct() {
        // إنشاء كائن من Order Model
        $this->orderModel = new Order();
        // إنشاء كائن من ValidationHelper
        $this->validator = new ValidationHelper();
    }
    
    // 📋 دالة عرض طلبات المستخدم
    public function index() {
        // لو المستخدم مش مسجل دخول، روحه لصفحة تسجيل الدخول
        if (!SessionHelper::isLoggedIn()) {
            $this->redirect('/login');
        }
        
        // جيب ID المستخدم من الـ Session
        $userId = SessionHelper::getUserId();
        // جيب كل طلبات المستخدم ده من قاعدة البيانات
        $orders = $this->orderModel->getUserOrders($userId);
        
        // جهز البيانات
        $data = [
            'title' => 'My Orders',  // عنوان الصفحة
            'orders' => $orders  // قائمة الطلبات
        ];
        
        // اعرض صفحة طلباتي
        $this->view('orders/index', $data);
    }
    
    // 🔍 دالة عرض تفاصيل طلب واحد
    public function show($orderId) {
        // لو المستخدم مش مسجل دخول، روحه لصفحة تسجيل الدخول
        if (!SessionHelper::isLoggedIn()) {
            $this->redirect('/login');
        }
        
        // جيب بيانات الطلب من قاعدة البيانات
        $order = $this->orderModel->findById($orderId);
        
        // تحقق إن الطلب موجود وإن المستخدم هو صاحب الطلب (حماية)
        // مينفعش مستخدم يشوف طلبات مستخدم تاني
        if (!$order || $order['user_id'] != SessionHelper::getUserId()) {
            // لو الطلب مش موجود أو مش بتاعه، اعرض صفحة 404
            $this->notFound();
            return;
        }
        
        // جيب المنتجات اللي في الطلب ده
        $orderItems = $this->orderModel->getOrderItems($orderId);
        
        // جهز البيانات
        $data = [
            'title' => 'Order #' . $order['order_number'],  // عنوان الصفحة
            'order' => $order,  // بيانات الطلب
            'orderItems' => $orderItems  // المنتجات في الطلب
        ];
        
        // اعرض صفحة تفاصيل الطلب
        $this->view('orders/show', $data);
    }
    
    // 💳 دالة صفحة الدفع (Checkout)
    public function checkout() {
        // لو المستخدم مش مسجل دخول، لازم يسجل دخول الأول
        if (!SessionHelper::isLoggedIn()) {
            SessionHelper::setFlash('error', 'لازم تسجل دخول الأول');
            $this->redirect('/login');
        }
        
        // لو السلة فاضية، مينفعش يكمل
        if (CartHelper::isEmpty()) {
            SessionHelper::setFlash('error', 'السلة فاضية');
            $this->redirect('/cart');
        }
        
        // تحقق من المخزون (لو منتج خلص، يقوله)
        $stockErrors = CartHelper::validateStock();
        if (!empty($stockErrors)) {
            // لو في منتجات خلصت، اعرض رسالة لكل منتج
            foreach ($stockErrors as $error) {
                SessionHelper::setFlash('error', $error);
            }
            // روحه للسلة عشان يشيل المنتجات اللي خلصت
            $this->redirect('/cart');
        }
        
        // لو المستخدم عامل Submit (ضغط على زر Place Order)
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // تحقق من CSRF Token (حماية)
            if (!SessionHelper::verifyCsrfToken($_POST['csrf_token'] ?? '')) {
                SessionHelper::setFlash('error', 'Invalid request');
                $this->redirect('/checkout');
            }
            
            // نضف البيانات اللي جت من الفورم
            $data = ValidationHelper::sanitize($_POST);
            
            // قواعد التحقق من بيانات الشحن
            $rules = [
                'shipping_name' => 'required|min:3|max:100',  // الاسم مطلوب
                'shipping_email' => 'required|email',  // البريد مطلوب وصحيح
                'shipping_phone' => 'required|min:10',  // الهاتف مطلوب
                'shipping_address' => 'required|min:10'  // العنوان مطلوب
            ];
            
            // تحقق من البيانات
            if ($this->validator->validate($data, $rules)) {
                // كل حاجة تمام، جيب السلة والمجموع
                $cart = CartHelper::getCart();
                $total = CartHelper::getTotal();
                
                // جهز بيانات الطلب
                $orderData = [
                    'user_id' => SessionHelper::getUserId(),  // ID المستخدم
                    'total_amount' => $total,  // المجموع الكلي
                    'shipping_name' => $data['shipping_name'],  // اسم المستلم
                    'shipping_email' => $data['shipping_email'],  // بريد المستلم
                    'shipping_phone' => $data['shipping_phone'],  // هاتف المستلم
                    'shipping_address' => $data['shipping_address'],  // عنوان الشحن
                    'notes' => $data['notes'] ?? null  // ملاحظات (اختياري)
                ];
                
                // أنشئ الطلب في قاعدة البيانات
                $orderId = $this->orderModel->create($orderData, $cart);
                
                // لو إنشاء الطلب نجح
                if ($orderId) {
                    // جيب بيانات الطلب (عشان نجيب رقم الطلب)
                    $order = $this->orderModel->findById($orderId);
                    $orderData['order_number'] = $order['order_number'];
                    
                    // جهز المنتجات بصيغة Stripe
                    $lineItems = StripeHelper::formatLineItems($cart);
                    // أنشئ جلسة دفع في Stripe
                    $session = StripeHelper::createCheckoutSession($orderData, $lineItems);
                    
                    // لو إنشاء جلسة Stripe نجح
                    if ($session) {
                        // احفظ Stripe session ID مع الطلب
                        $this->orderModel->updatePaymentStatus($orderId, 'pending', $session->id);
                        
                        // فرغ السلة (الطلب اتعمل خلاص)
                        CartHelper::clear();
                        
                        // حول المستخدم لصفحة Stripe للدفع
                        if (isset($session->url)) {
                            header("Location: " . $session->url);
                            exit;
                        } else {
                            // لو مفيش URL، روح لصفحة النجاح مباشرة
                            $this->redirect('/orders/success?order_id=' . $orderId);
                        }
                    } else {
                        // لو إنشاء جلسة Stripe فشل
                        SessionHelper::setFlash('error', 'فيه مشكلة في الدفع. تحقق من مفاتيح Stripe.');
                        $this->redirect('/checkout');
                    }
                } else {
                    // لو إنشاء الطلب فشل
                    SessionHelper::setFlash('error', 'فيه مشكلة في إنشاء الطلب');
                    $this->redirect('/checkout');
                }
            } else {
                // لو في أخطاء في البيانات، اعرض أول خطأ
                SessionHelper::setFlash('error', $this->validator->getFirstError());
                $this->redirect('/checkout');
            }
        }
        
        // جيب السلة والمجموع عشان نعرضهم في الصفحة
        $cart = CartHelper::getCart();
        $total = CartHelper::getTotal();
        
        // جهز البيانات لصفحة Checkout
        $data = [
            'title' => 'Checkout',  // عنوان الصفحة
            'cart' => $cart,  // محتويات السلة
            'total' => $total,  // المجموع الكلي
            'csrf_token' => SessionHelper::generateCsrfToken()  // CSRF Token
        ];
        
        // اعرض صفحة Checkout
        $this->view('orders/checkout', $data);
    }
    
    // ✅ دالة صفحة النجاح (بعد الدفع الناجح)
    public function success() {
        // لو المستخدم مش مسجل دخول، روحه للصفحة الرئيسية
        if (!SessionHelper::isLoggedIn()) {
            $this->redirect('/');
        }
        
        // جيب Stripe session ID من الـ URL (Stripe بيبعته لما يرجع المستخدم)
        $sessionId = $_GET['session_id'] ?? null;
        // جيب order ID من الـ URL (لو موجود)
        $orderId = $_GET['order_id'] ?? null;
        
        // لو في session ID (يعني جاي من Stripe)
        if ($sessionId) {
            // جيب بيانات الجلسة من Stripe
            $session = StripeHelper::retrieveSession($sessionId);
            
            // لو الجلسة موجودة
            if ($session) {
                // جيب حالة الدفع من Stripe
                $paymentStatus = $session->payment_status ?? null;
                
                // لو الدفع نجح (paid)
                if ($paymentStatus === 'paid') {
                    // جيب رقم الطلب من metadata (احنا بعتناه لـ Stripe)
                    $orderNumber = $session->metadata->order_number ?? null;
                    
                    // لو رقم الطلب موجود
                    if ($orderNumber) {
                        // دور على الطلب في قاعدة البيانات
                        $order = $this->orderModel->findByOrderNumber($orderNumber);
                        if ($order) {
                            // حدث حالة الدفع لـ "paid" (مدفوع)
                            $this->orderModel->updatePaymentStatus($order['id'], 'paid', $sessionId);
                            // حدث حالة الطلب لـ "processing" (قيد التجهيز)
                            $this->orderModel->updateStatus($order['id'], 'processing');
                        }
                    } elseif ($orderId) {
                        // لو مفيش order_number، استخدم order_id (احتياطي)
                        $this->orderModel->updatePaymentStatus($orderId, 'paid', $sessionId);
                        $this->orderModel->updateStatus($orderId, 'processing');
                    }
                }
            }
        }
        
        // جهز البيانات لصفحة النجاح
        $data = ['title' => 'Order Success'];
        // اعرض صفحة النجاح
        $this->view('orders/success', $data);
    }
    
    // ❌ دالة صفحة الإلغاء (لو المستخدم ألغى الدفع من Stripe)
    public function cancel() {
        // جهز البيانات لصفحة الإلغاء
        $data = ['title' => 'Order Cancelled'];
        // اعرض صفحة الإلغاء
        $this->view('orders/cancel', $data);
    }
}

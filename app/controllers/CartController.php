<?php
// 🛒 CartController - المتحكم الخاص بسلة التسوق
// بيرث من HomeController عشان يستخدم دوال view و redirect

class CartController extends HomeController {
    
    // 📋 دالة عرض السلة
    public function index() {
        // جيب محتويات السلة من الـ Session
        $cart = CartHelper::getCart();
        // احسب المجموع الكلي لكل المنتجات في السلة
        $total = CartHelper::getTotal();
        
        // جهز البيانات اللي هتروح للـ View
        $data = [
            'title' => 'Shopping Cart',  // عنوان الصفحة
            'cart' => $cart,  // محتويات السلة
            'total' => $total  // المجموع الكلي
        ];
        
        // اعرض صفحة السلة
        $this->view('cart/index', $data);
    }
    
    // ➕ دالة إضافة منتج للسلة
    public function add() {
        // لو الطلب POST (المستخدم ضغط على زر Add to Cart)
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // جيب البيانات (ممكن تكون JSON من AJAX أو POST عادي)
            $input = json_decode(file_get_contents('php://input'), true);
            
            // جيب ID المنتج (من JSON أو POST)
            $productId = (int)($input['product_id'] ?? $_POST['product_id'] ?? 0);
            // جيب الكمية (لو مش موجودة، الافتراضي 1)
            $quantity = (int)($input['quantity'] ?? $_POST['quantity'] ?? 1);
            
            // تحقق إن ID المنتج والكمية صحيحين
            if ($productId > 0 && $quantity > 0) {
                // حاول تضيف المنتج للسلة
                if (CartHelper::add($productId, $quantity)) {
                    // لو الإضافة نجحت
                    if (isset($input['product_id'])) {
                        // لو الطلب جاي من AJAX، ارجع JSON
                        $this->json(['success' => true, 'message' => 'تم إضافة المنتج للسلة']);
                    } else {
                        // لو طلب عادي، اعرض رسالة وحول للسلة
                        SessionHelper::setFlash('success', 'تم إضافة المنتج للسلة');
                        $this->redirect('/cart');
                    }
                } else {
                    // لو الإضافة فشلت (المنتج مش متاح أو المخزون خلص)
                    if (isset($input['product_id'])) {
                        // لو AJAX، ارجع JSON بخطأ
                        $this->json(['success' => false, 'message' => 'المنتج مش متاح'], 400);
                    } else {
                        // لو طلب عادي، اعرض رسالة خطأ
                        SessionHelper::setFlash('error', 'المنتج مش متاح');
                        $this->redirect('/cart');
                    }
                }
            }
        }
        
        // لو حصل أي حاجة غلط، روح للسلة
        $this->redirect('/cart');
    }
    
    // 🔄 دالة تحديث كمية منتج في السلة
    public function update() {
        // لو الطلب POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // جيب البيانات (JSON أو POST)
            $input = json_decode(file_get_contents('php://input'), true);
            
            // جيب ID المنتج
            $productId = (int)($input['product_id'] ?? $_POST['product_id'] ?? 0);
            // جيب الكمية الجديدة
            $quantity = (int)($input['quantity'] ?? $_POST['quantity'] ?? 1);
            
            // تحقق إن ID المنتج صحيح
            if ($productId > 0) {
                // حدث الكمية في السلة
                CartHelper::update($productId, $quantity);
                
                // لو الطلب من AJAX
                if (isset($input['product_id'])) {
                    // ارجع JSON
                    $this->json(['success' => true]);
                } else {
                    // لو طلب عادي، اعرض رسالة نجاح
                    SessionHelper::setFlash('success', 'تم تحديث السلة');
                }
            }
        }
        
        // روح للسلة
        $this->redirect('/cart');
    }
    
    // ❌ دالة حذف منتج من السلة
    public function remove($productId) {
        // حول ID المنتج لرقم صحيح
        $productId = (int)$productId;
        
        // تحقق إن ID صحيح
        if ($productId > 0) {
            // احذف المنتج من السلة
            CartHelper::remove($productId);
            // اعرض رسالة نجاح
            SessionHelper::setFlash('success', 'تم حذف المنتج من السلة');
        }
        
        // روح للسلة
        $this->redirect('/cart');
    }
    
    // 🗑️ دالة تفريغ السلة بالكامل
    public function clear() {
        // امسح كل المنتجات من السلة
        CartHelper::clear();
        // اعرض رسالة نجاح
        SessionHelper::setFlash('success', 'تم تفريغ السلة');
        // روح للسلة
        $this->redirect('/cart');
    }
    
    // 🔢 دالة حساب عدد المنتجات في السلة (AJAX)
    // بتستخدم في شريط التنقل العلوي عشان تعرض عدد المنتجات
    public function count() {
        // احسب عدد المنتجات في السلة
        $count = CartHelper::getCount();
        // ارجع العدد كـ JSON
        $this->json(['count' => $count]);
    }
}

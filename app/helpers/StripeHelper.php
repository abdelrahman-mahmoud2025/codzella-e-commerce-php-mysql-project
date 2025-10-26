<?php
// 💳 StripeHelper - مساعد الدفع عبر Stripe
// بيتعامل مع Stripe API للدفع الإلكتروني

class StripeHelper {
    
    // متغير للتحقق من تهيئة Stripe
    private static $stripe = null;
    
    // 🔧 تهيئة Stripe SDK
    private static function init() {
        // لو Stripe مش متهيأ بعد
        if (self::$stripe === null) {
            // حمل Composer autoloader (عشان نستخدم Stripe SDK)
            require_once __DIR__ . '/../../vendor/autoload.php';
            // اضبط المفتاح السري لـ Stripe
            \Stripe\Stripe::setApiKey(STRIPE_SECRET_KEY);
            // علم إن Stripe اتهيأ
            self::$stripe = true;
        }
    }
    
    // 🛒 إنشاء جلسة دفع في Stripe (Checkout Session)
    public static function createCheckoutSession($orderData, $lineItems) {
        try {
            // هيئ Stripe الأول
            self::init();
            
            // تحقق إن في منتجات (line items)
            if (empty($lineItems)) {
                // لو مفيش منتجات، سجل خطأ
                error_log("Stripe Error: No line items provided");
                return false;
            }
            
            // أنشئ جلسة دفع في Stripe
            $session = \Stripe\Checkout\Session::create([
                'payment_method_types' => ['card'],  // نوع الدفع (بطاقة ائتمان)
                'line_items' => $lineItems,  // المنتجات
                'mode' => 'payment',  // الوضع (دفعة واحدة)
                'success_url' => APP_URL . '/orders/success?session_id={CHECKOUT_SESSION_ID}',  // صفحة النجاح
                'cancel_url' => APP_URL . '/orders/cancel',  // صفحة الإلغاء
                'customer_email' => $orderData['shipping_email'],  // بريد العميل
                'metadata' => [
                    // بيانات إضافية (عشان نعرف الطلب بعدين)
                    'order_number' => $orderData['order_number'] ?? 'N/A'
                ]
            ]);
            
            // ارجع الجلسة (فيها URL عشان نحول المستخدم لصفحة الدفع)
            return $session;
            
        } catch (\Stripe\Exception\ApiErrorException $e) {
            // لو حصل خطأ من Stripe API
            error_log("Stripe API Error: " . $e->getMessage());
            error_log("Stripe Error Code: " . $e->getError()->code);
            return false;
        } catch (Exception $e) {
            // لو حصل خطأ عام
            error_log("Stripe General Error: " . $e->getMessage());
            return false;
        }
    }
    
    // 📖 استرجاع جلسة دفع من Stripe (بعد ما المستخدم يرجع)
    public static function retrieveSession($sessionId) {
        // هيئ Stripe
        self::init();
        
        try {
            // اجلب بيانات الجلسة من Stripe
            return \Stripe\Checkout\Session::retrieve($sessionId);
            
        } catch (Exception $e) {
            // لو حصل خطأ
            error_log("Stripe Error: " . $e->getMessage());
            return false;
        }
    }
    
    // 📦 تنسيق المنتجات بصيغة Stripe
    // Stripe بيحتاج المنتجات بصيغة معينة
    public static function formatLineItems($cartItems) {
        // array فاضي للمنتجات المنسقة
        $lineItems = [];
        
        // لكل منتج في السلة
        foreach ($cartItems as $item) {
            // أضف المنتج بصيغة Stripe
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'usd',  // العملة (دولار)
                    'product_data' => [
                        'name' => $item['name'],  // اسم المنتج
                    ],
                    'unit_amount' => $item['price'] * 100,  // السعر بالسنت (1 دولار = 100 سنت)
                ],
                'quantity' => $item['quantity'],  // الكمية
            ];
        }
        
        // ارجع المنتجات المنسقة
        return $lineItems;
    }
}

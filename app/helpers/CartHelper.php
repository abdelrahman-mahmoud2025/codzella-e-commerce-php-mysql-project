<?php
// 🛒 CartHelper - مساعد إدارة سلة التسوق
// السلة محفوظة في الـ Session

class CartHelper {
    
    // اسم المفتاح اللي بنحفظ بيه السلة في الـ Session
    private static $cartKey = 'shopping_cart';
    
    // ➕ إضافة منتج للسلة
    public static function add($productId, $quantity = 1) {
        // جيب السلة الحالية
        $cart = self::getCart();
        
        // لو المنتج موجود في السلة خلاص
        if (isset($cart[$productId])) {
            // زود الكمية بس
            $cart[$productId]['quantity'] += $quantity;
        } else {
            // لو المنتج مش موجود، جيب بياناته من قاعدة البيانات
            $product = new Product();
            $productData = $product->findById($productId);
            
            // تحقق إن المنتج موجود وإن المخزون كافي
            if ($productData && $productData['stock'] >= $quantity) {
                // أضف المنتج للسلة
                $cart[$productId] = [
                    'id' => $productData['id'],  // ID المنتج
                    'name' => $productData['name'],  // اسم المنتج
                    'price' => $productData['sale_price'] ?? $productData['price'],  // السعر (لو في تخفيض، استخدمه)
                    'image' => $productData['image'],  // صورة المنتج
                    'quantity' => $quantity,  // الكمية
                    'stock' => $productData['stock']  // المخزون المتاح
                ];
            } else {
                // لو المنتج مش متاح أو المخزون مش كافي، ارجع false
                return false;
            }
        }
        
        // احفظ السلة المحدثة
        self::saveCart($cart);
        // ارجع true (نجحت)
        return true;
    }
    
    // 🔄 تحديث كمية منتج في السلة
    public static function update($productId, $quantity) {
        // جيب السلة
        $cart = self::getCart();
        
        // لو المنتج موجود في السلة
        if (isset($cart[$productId])) {
            // لو الكمية الجديدة صفر أو أقل، احذف المنتج
            if ($quantity <= 0) {
                unset($cart[$productId]);
            } else {
                // لو لأ، حدث الكمية
                $cart[$productId]['quantity'] = $quantity;
            }
            // احفظ السلة
            self::saveCart($cart);
            return true;
        }
        
        // لو المنتج مش موجود، ارجع false
        return false;
    }
    
    // ❌ حذف منتج من السلة
    public static function remove($productId) {
        // جيب السلة
        $cart = self::getCart();
        
        // لو المنتج موجود
        if (isset($cart[$productId])) {
            // احذفه
            unset($cart[$productId]);
            // احفظ السلة
            self::saveCart($cart);
            return true;
        }
        
        // لو مش موجود، ارجع false
        return false;
    }
    
    // 📖 جيب السلة من الـ Session
    public static function getCart() {
        // ارجع السلة (لو مش موجودة، ارجع array فاضي)
        return SessionHelper::get(self::$cartKey, []);
    }
    
    // 💾 احفظ السلة في الـ Session
    public static function saveCart($cart) {
        // احفظ السلة
        SessionHelper::set(self::$cartKey, $cart);
    }
    
    // 🗑️ فرغ السلة بالكامل
    public static function clear() {
        // احذف السلة من الـ Session
        SessionHelper::remove(self::$cartKey);
    }
    
    // 💰 احسب المجموع الكلي للسلة
    public static function getTotal() {
        // جيب السلة
        $cart = self::getCart();
        // ابدأ من صفر
        $total = 0;
        
        // لكل منتج في السلة
        foreach ($cart as $item) {
            // اجمع (السعر × الكمية)
            $total += $item['price'] * $item['quantity'];
        }
        
        // ارجع المجموع
        return $total;
    }
    
    // 🔢 احسب عدد المنتجات في السلة
    public static function getCount() {
        // جيب السلة
        $cart = self::getCart();
        // ابدأ من صفر
        $count = 0;
        
        // لكل منتج في السلة
        foreach ($cart as $item) {
            // اجمع الكمية
            $count += $item['quantity'];
        }
        
        // ارجع العدد الكلي
        return $count;
    }
    
    // ❓ تحقق: هل السلة فاضية؟
    public static function isEmpty() {
        // ارجع true لو السلة فاضية، false لو فيها حاجة
        return empty(self::getCart());
    }
    
    // ✅ تحقق من المخزون (قبل الدفع)
    public static function validateStock() {
        // جيب السلة
        $cart = self::getCart();
        // إنشاء كائن Product للتحقق من المخزون
        $product = new Product();
        // array للأخطاء
        $errors = [];
        
        // لكل منتج في السلة
        foreach ($cart as $productId => $item) {
            // جيب بيانات المنتج الحالية من قاعدة البيانات
            $productData = $product->findById($productId);
            
            // لو المنتج اتحذف من قاعدة البيانات
            if (!$productData) {
                // أضف رسالة خطأ
                $errors[] = "المنتج '{$item['name']}' مش متاح دلوقتي";
                // احذفه من السلة
                self::remove($productId);
            } 
            // لو المخزون أقل من الكمية المطلوبة
            elseif ($productData['stock'] < $item['quantity']) {
                // أضف رسالة خطأ
                $errors[] = "متاح فقط {$productData['stock']} قطعة من '{$item['name']}'";
            }
        }
        
        // ارجع array الأخطاء (لو فاضي يبقى كل حاجة تمام)
        return $errors;
    }
}

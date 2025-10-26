````markdown
# 🧭 Windsurf Setup & Development Guide  
### Project: CodeZilla Digital Store (PHP/MySQL Edition)

---

## ⚙️ 1. Project Overview
CodeZilla Store is a **vanilla PHP + MySQL** e-commerce project built to teach **Full-Stack fundamentals** using a **manual MVC pattern**.  
The project includes a **Stripe test integration**, **admin dashboard**, and **user storefront**.

---

## 🧩 2. Folder Structure Setup

### Step 1 — Create Project Directory
```bash
mkdir codezilla-store && cd codezilla-store
````

### Step 2 — Create Core Folders

```bash
mkdir app config public src views uploads
mkdir app/controllers app/models app/helpers
mkdir views/layout views/products views/auth views/admin
```

### Step 3 — Create Core Files

```bash
touch index.php .htaccess composer.json
touch config/database.php config/env.php
touch app/controllers/ProductController.php
touch app/models/Product.php
touch views/layout/header.php views/layout/footer.php
```

---

## ⚡ 3. Basic Configuration

### Step 1 — Configure `.htaccess`

Redirect all requests to `index.php` for clean URLs.

```apache
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ index.php?url=$1 [QSA,L]
```

### Step 2 — Configure `config/env.php`

```php
<?php
return [
  'DB_HOST' => 'localhost',
  'DB_NAME' => 'codezilla_db',
  'DB_USER' => 'root',
  'DB_PASS' => '',
  'STRIPE_KEY' => 'your_test_public_key',
  'STRIPE_SECRET' => 'your_test_secret_key'
];
```

### Step 3 — Connect Database (`config/database.php`)

```php
<?php
$config = require 'env.php';
try {
  $pdo = new PDO(
    "mysql:host={$config['DB_HOST']};dbname={$config['DB_NAME']}",
    $config['DB_USER'],
    $config['DB_PASS'],
    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
  );
} catch (PDOException $e) {
  die("Database connection failed: " . $e->getMessage());
}
```

---

## 🧠 4. MVC Routing (index.php)

```php
<?php
require_once 'config/database.php';

$url = isset($_GET['url']) ? explode('/', filter_var($_GET['url'], FILTER_SANITIZE_URL)) : ['home'];
$controllerName = ucfirst($url[0]) . 'Controller';
$method = $url[1] ?? 'index';
$params = array_slice($url, 2);

$controllerPath = "app/controllers/$controllerName.php";
if (file_exists($controllerPath)) {
  require_once $controllerPath;
  $controller = new $controllerName();
  if (method_exists($controller, $method)) {
    call_user_func_array([$controller, $method], $params);
  } else {
    echo "Method not found.";
  }
} else {
  echo "Controller not found.";
}
```

---

## 💾 5. Database Schema (MySQL)

### Step 1 — Create Database

```sql
CREATE DATABASE codezilla_db;
USE codezilla_db;
```

### Step 2 — Create Tables

```sql
CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100),
  email VARCHAR(100) UNIQUE,
  password VARCHAR(255),
  is_admin TINYINT DEFAULT 0
);

CREATE TABLE categories (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100)
);

CREATE TABLE products (
  id INT AUTO_INCREMENT PRIMARY KEY,
  category_id INT,
  name VARCHAR(150),
  description TEXT,
  price DECIMAL(10,2),
  stock_quantity INT,
  image_url VARCHAR(255),
  FOREIGN KEY (category_id) REFERENCES categories(id)
);

CREATE TABLE orders (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT,
  order_date DATETIME DEFAULT CURRENT_TIMESTAMP,
  total_amount DECIMAL(10,2),
  shipping_address TEXT,
  status VARCHAR(50) DEFAULT 'Pending',
  FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE order_items (
  id INT AUTO_INCREMENT PRIMARY KEY,
  order_id INT,
  product_id INT,
  quantity INT,
  price_at_purchase DECIMAL(10,2),
  FOREIGN KEY (order_id) REFERENCES orders(id),
  FOREIGN KEY (product_id) REFERENCES products(id)
);
```

---

## 💳 6. Stripe Test Integration

### Step 1 — Install Stripe SDK

```bash
composer require stripe/stripe-php
```

### Step 2 — Create `app/helpers/StripeHelper.php`

```php
<?php
require 'vendor/autoload.php';
$config = require __DIR__ . '/../../config/env.php';
\Stripe\Stripe::setApiKey($config['STRIPE_SECRET']);
```

### Step 3 — Example Payment Handler

```php
$session = \Stripe\Checkout\Session::create([
  'line_items' => [[
    'price_data' => [
      'currency' => 'usd',
      'product_data' => ['name' => 'Sample Book'],
      'unit_amount' => 1500,
    ],
    'quantity' => 1,
  ]],
  'mode' => 'payment',
  'success_url' => 'http://localhost:8000/success',
  'cancel_url' => 'http://localhost:8000/cancel',
]);
header("Location: " . $session->url);
exit;
```

---

## 🧭 7. Run the Project in Windsurf

```bash
php -S localhost:8000 -t public
```

Then open:
👉 **[http://localhost:8000](http://localhost:8000)**

---

## 🧱 8. Development Phases

| Phase       | Description                                    |
| ----------- | ---------------------------------------------- |
| **Phase 1** | Setup folder structure + MVC base              |
| **Phase 2** | Implement user authentication (register/login) |
| **Phase 3** | Add product CRUD (Admin)                       |
| **Phase 4** | Create cart + checkout with Stripe             |
| **Phase 5** | Test, refine, and polish UX                    |

---

## ✅ 9. Best Practices

* Use `password_hash()` & `password_verify()` for passwords.
* Sanitize all input with `filter_var()` and `htmlspecialchars()`.
* Validate sessions on every protected page.
* Separate public and admin routes clearly.
* Use environment variables from `env.php` instead of hardcoding.

---

## 🏁 10. Final Output

At the end of this guide, you’ll have:

* A fully functional **CodeZilla Store**.
* Secure user system + admin panel.
* Integrated **Stripe payment test** flow.
* Complete PHP MVC educational reference.

---

> **Pro Tip:** Keep your logic pure, code clean, and never let frameworks steal your growth curve 🚀

```
```

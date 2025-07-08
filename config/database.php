<?php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'plant_shop');

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create database if not exists
$sql = "CREATE DATABASE IF NOT EXISTS " . DB_NAME;
if ($conn->query($sql) === TRUE) {
    $conn->select_db(DB_NAME);
} else {
    die("Error creating database: " . $conn->error);
}

// Create users table
$sql = "CREATE TABLE IF NOT EXISTS users (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    phone VARCHAR(20) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if (!$conn->query($sql)) {
    die("Error creating users table: " . $conn->error);
}

// Create products table
$sql = "CREATE TABLE IF NOT EXISTS products (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    category VARCHAR(50) NOT NULL,
    image_url VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if (!$conn->query($sql)) {
    die("Error creating products table: " . $conn->error);
}

// Create orders table
$sql = "CREATE TABLE IF NOT EXISTS orders (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    user_id INT(11) NOT NULL,
    address TEXT,
    name VARCHAR(100),
    phone VARCHAR(20),
    total_amount DECIMAL(10,2) NOT NULL,
    status VARCHAR(20) DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
)";

if (!$conn->query($sql)) {
    die("Error creating orders table: " . $conn->error);
}
// Add address, name, phone columns to orders if not exist
$conn->query("ALTER TABLE orders ADD COLUMN IF NOT EXISTS address TEXT AFTER user_id");
$conn->query("ALTER TABLE orders ADD COLUMN IF NOT EXISTS name VARCHAR(100) AFTER address");
$conn->query("ALTER TABLE orders ADD COLUMN IF NOT EXISTS phone VARCHAR(20) AFTER name");

// Create order_items table
$sql = "CREATE TABLE IF NOT EXISTS order_items (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    order_id INT(11) NOT NULL,
    product_id INT(11) NOT NULL,
    quantity INT(11) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id),
    FOREIGN KEY (product_id) REFERENCES products(id)
)";

if (!$conn->query($sql)) {
    die("Error creating order_items table: " . $conn->error);
}

// Add admin table
$sql = "CREATE TABLE IF NOT EXISTS admins (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
if (!$conn->query($sql)) {
    die("Error creating admins table: " . $conn->error);
}
// Add addresses table
$sql = "CREATE TABLE IF NOT EXISTS addresses (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    user_id INT(11) NOT NULL,
    address TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
if (!$conn->query($sql)) {
    die("Error creating addresses table: " . $conn->error);
}
// Add discount_codes table
$sql = "CREATE TABLE IF NOT EXISTS discount_codes (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(50) UNIQUE NOT NULL,
    percent INT(3) NOT NULL,
    max_uses INT(11) DEFAULT 1,
    used_count INT(11) DEFAULT 0,
    expires_at DATETIME,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
if (!$conn->query($sql)) {
    die("Error creating discount_codes table: " . $conn->error);
}
// Add messages table
$sql = "CREATE TABLE IF NOT EXISTS messages (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    user_id INT(11),
    name VARCHAR(100),
    email VARCHAR(100),
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
if (!$conn->query($sql)) {
    die("Error creating messages table: " . $conn->error);
}
// Add product ratings
$sql = "CREATE TABLE IF NOT EXISTS product_ratings (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    product_id INT(11) NOT NULL,
    user_id INT(11) NOT NULL,
    rating INT(1) NOT NULL,
    comment TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
if (!$conn->query($sql)) {
    die("Error creating product_ratings table: " . $conn->error);
}
// Add stock to products
$sql = "ALTER TABLE products ADD COLUMN IF NOT EXISTS stock INT(11) DEFAULT 10";
$conn->query($sql); // ignore error if already exists
// Create categories table
$sql = "CREATE TABLE IF NOT EXISTS categories (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
if (!$conn->query($sql)) {
    die("Error creating categories table: " . $conn->error);
}
// Seed default categories if empty
$check = $conn->query("SELECT COUNT(*) as cnt FROM categories");
$row = $check->fetch_assoc();
if ($row['cnt'] == 0) {
    $cats = [
        ['آپارتمانی', 'گیاهان مناسب فضای داخلی'],
        ['زینتی', 'گیاهان زیبا و مقاوم'],
        ['دارویی', 'گیاهان با خواص درمانی'],
    ];
    $stmt = $conn->prepare("INSERT INTO categories (name, description) VALUES (?, ?)");
    foreach ($cats as $c) {
        $stmt->bind_param("ss", $c[0], $c[1]);
        $stmt->execute();
    }
}
// Seed admin user
$admin_email = 'admin@plant-shop.ir';
$admin_pass = password_hash('Admin@1234', PASSWORD_DEFAULT);
$conn->query("INSERT IGNORE INTO admins (email, password) VALUES ('$admin_email', '$admin_pass')");
// Seed 9 products if table is empty
$check = $conn->query("SELECT COUNT(*) as cnt FROM products");
$row = $check->fetch_assoc();
if ($row['cnt'] == 0) {
    $products = [
        ['فیکوس الاستیکا','گیاه مقاوم و زیبا مناسب فضای داخلی.',350000,'آپارتمانی','assets/images/kendal-TW2bfT_tWDI-unsplash.jpg',4.5,10],
        ['سانسوریا','گیاه تصفیه‌کننده هوا و کم‌نیاز به نور.',220000,'آپارتمانی','assets/images/alex-perri-bmM_IdLd1SA-unsplash.jpg',4.8,10],
        ['کاکتوس اریوکارپوس','کاکتوس کمیاب و کلکسیونی.',480000,'زینتی','assets/images/jackie-dilorenzo-RyLsRzy9jIA-unsplash.jpg',4.2,10],
        ['پتوس طلایی','گیاه رونده و مقاوم با برگ‌های طلایی.',180000,'آپارتمانی','assets/images/ren-ran-bBiuSdck8tU-unsplash.jpg',4.7,10],
        ['آلوئه ورا','گیاه دارویی با خواص درمانی فراوان.',250000,'دارویی','assets/images/ceyda-ciftci-dDVU6D_6T80-unsplash.jpg',4.9,10],
        ['زامیفولیا','گیاه مقاوم به کم‌آبی و نور کم.',320000,'آپارتمانی','assets/images/pexels-kate-amos-1408770-2718447.jpg',4.6,10],
        ['پپرومیا','گیاه کوچک و مناسب میز کار.',150000,'زینتی','assets/images/pexels-iriser-1005715.jpg',4.3,10],
        ['فیلودندرون','گیاه برگ‌درشت و بسیار زیبا.',410000,'آپارتمانی','assets/images/pexels-daniel-1055408.jpg',4.4,10],
        ['کالاتیا','گیاه برگ‌دار با نقش و نگار خاص.',370000,'زینتی','assets/images/pexels-valeriya-827518.jpg',4.1,10],
    ];
    $stmt = $conn->prepare("INSERT INTO products (name, description, price, category, image_url, created_at, stock) VALUES (?, ?, ?, ?, ?, NOW(), ?)");
    foreach ($products as $p) {
        $stmt->bind_param("ssdssi", $p[0], $p[1], $p[2], $p[3], $p[4], $p[6]);
        $stmt->execute();
    }
}
// Create tickets table
$sql = "CREATE TABLE IF NOT EXISTS tickets (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    user_id INT(11) NOT NULL,
    subject VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    status ENUM('open','answered','closed') DEFAULT 'open',
    admin_reply TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
if (!$conn->query($sql)) {
    die("Error creating tickets table: " . $conn->error);
}
?> 
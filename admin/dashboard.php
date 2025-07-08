<?php
session_start();
require_once '../config/database.php';
// تعداد کاربران
$user_count = 0;
$res = $conn->query("SELECT COUNT(*) as cnt FROM users");
if ($row = $res->fetch_assoc()) $user_count = $row['cnt'];
// تعداد محصولات
$product_count = 0;
$res = $conn->query("SELECT COUNT(*) as cnt FROM products");
if ($row = $res->fetch_assoc()) $product_count = $row['cnt'];
// تعداد سفارشات
$order_count = 0;
$res = $conn->query("SELECT COUNT(*) as cnt FROM orders");
if ($row = $res->fetch_assoc()) $order_count = $row['cnt'];
// تعداد پیام‌ها
$message_count = 0;
$res = $conn->query("SELECT COUNT(*) as cnt FROM messages");
if ($row = $res->fetch_assoc()) $message_count = $row['cnt'];
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>داشبورد ادمین | پنل مدیریت فروشگاه گیاهان</title>
    <!-- Bootstrap RTL CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- AOS CSS -->
    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
    <!-- Vazirmatn Font -->
    <link href="https://cdn.jsdelivr.net/gh/rastikerdar/vazirmatn@v33.003/Vazirmatn-font-face.css" rel="stylesheet" type="text/css" />
    <style>
        body {
            font-family: 'Vazirmatn', sans-serif;
            background: #f8f9fa;
        }

        .admin-header {
            background: linear-gradient(90deg, #388e3c 0%, #43a047 100%);
            color: #fff;
            padding: 1rem 0;
            box-shadow: 0 2px 8px rgba(60, 60, 60, 0.08);
        }

        .admin-header .navbar-brand {
            font-weight: bold;
            font-size: 1.5rem;
            color: #fff;
        }

        .admin-header .nav-link,
        .admin-header .nav-link:visited {
            color: #e0f2f1;
            font-size: 1.1rem;
            margin-left: 1rem;
        }

        .admin-header .nav-link.active,
        .admin-header .nav-link:hover {
            color: #ffd600;
            font-weight: bold;
        }

        .admin-dashboard {
            padding: 2rem 0;
        }

        .stat-card {
            border-radius: 16px;
            box-shadow: 0 4px 16px rgba(60, 60, 60, 0.08);
            background: #fff;
            padding: 2rem 1.5rem;
            text-align: center;
            margin-bottom: 2rem;
        }

        .stat-card .icon {
            font-size: 2.5rem;
            color: #43a047;
            margin-bottom: 1rem;
        }

        .stat-card .stat-title {
            font-size: 1.1rem;
            color: #388e3c;
            margin-bottom: 0.5rem;
        }

        .stat-card .stat-value {
            font-size: 2rem;
            font-weight: bold;
            color: #222;
        }

        .admin-footer {
            background: linear-gradient(90deg, #388e3c 0%, #43a047 100%);
            color: #fff;
            border-radius: 24px 24px 0 0;
            box-shadow: 0 -2px 16px rgba(60, 60, 60, 0.08);
            margin-top: 60px;
            padding: 1.5rem 0 1rem 0;
            text-align: center;
        }

        @media (max-width: 768px) {
            .stat-card {
                padding: 1.2rem 0.5rem;
            }
        }
    </style>
</head>

<body>
    <!-- Admin Header -->
    <nav class="navbar navbar-expand-lg admin-header">
        <div class="container-fluid">
            <a class="navbar-brand" href="dashboard.php"><i class="fas fa-seedling me-2"></i>پنل مدیریت گیاهان سبز</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNavbar" aria-controls="adminNavbar" aria-expanded="false" aria-label="تغییر منو">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="adminNavbar">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link active" href="dashboard.php"><i class="fas fa-home"></i> داشبورد</a></li>
                    <li class="nav-item"><a class="nav-link" href="products.php"><i class="fas fa-leaf"></i> محصولات</a></li>
                    <li class="nav-item"><a class="nav-link" href="categories.php"><i class="fas fa-list"></i> دسته‌بندی‌ها</a></li>
                    <li class="nav-item"><a class="nav-link" href="users.php"><i class="fas fa-users"></i> کاربران</a></li>
                    <li class="nav-item"><a class="nav-link" href="orders.php"><i class="fas fa-shopping-cart"></i> سفارشات</a></li>
                    <li class="nav-item"><a class="nav-link" href="messages.php"><i class="fas fa-envelope"></i> پیام‌ها</a></li>
                    <li class="nav-item"><a class="nav-link" href="../index.php"><i class="fas fa-store"></i> مشاهده سایت</a></li>
                    <li class="nav-item"><a class="nav-link" href="logout.php"><i class="fas fa-sign-out-alt"></i> خروج</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <!-- Admin Dashboard Content -->
    <div class="container admin-dashboard" data-aos="fade-up">
        <div class="row g-4">
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="icon"><i class="fas fa-users"></i></div>
                    <div class="stat-title">تعداد کاربران</div>
                    <div class="stat-value"><?php echo $user_count; ?></div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="icon"><i class="fas fa-leaf"></i></div>
                    <div class="stat-title">تعداد محصولات</div>
                    <div class="stat-value"><?php echo $product_count; ?></div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="icon"><i class="fas fa-shopping-cart"></i></div>
                    <div class="stat-title">تعداد سفارشات</div>
                    <div class="stat-value"><?php echo $order_count; ?></div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="icon"><i class="fas fa-envelope"></i></div>
                    <div class="stat-title">پیام‌های جدید</div>
                    <div class="stat-value"><?php echo $message_count; ?></div>
                </div>
            </div>
        </div>
        <div class="row mt-5">
            <div class="col-12">
                <div class="card shadow border-0">
                    <div class="card-body">
                        <h4 class="mb-3"><i class="fas fa-info-circle text-success me-2"></i>خوش آمدید!</h4>
                        <p>به پنل مدیریت فروشگاه گیاهان سبز خوش آمدید. از منوی بالا می‌توانید بخش‌های مختلف سایت را مدیریت کنید.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Admin Footer -->
    <footer class="admin-footer mt-5">
        <div class="container">
            <h5>پنل مدیریت فروشگاه گیاهان سبز</h5>
            <p class="mb-0">&copy; 2024 Plant Shop Admin Panel</p>
        </div>
    </footer>
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@next/dist/aos.js"></script>
    <script>
        AOS.init();
    </script>
</body>

</html>
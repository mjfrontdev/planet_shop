<?php
require_once '../config/database.php';
$orders = [];
$sql = "SELECT o.*, u.name as user_name FROM orders o JOIN users u ON o.user_id = u.id ORDER BY o.created_at DESC";
$res = $conn->query($sql);
while ($row = $res->fetch_assoc()) {
    $orders[] = $row;
}
// Handle status update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ship_order_id'])) {
    $oid = intval($_POST['ship_order_id']);
    $conn->query("UPDATE orders SET status='shipped' WHERE id=$oid");
    header('Location: orders.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>مدیریت سفارشات | پنل ادمین فروشگاه گیاهان</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
    <link href="https://cdn.jsdelivr.net/gh/rastikerdar/vazirmatn@v33.003/Vazirmatn-font-face.css" rel="stylesheet" type="text/css" />
    <style>
        body { font-family: 'Vazirmatn', sans-serif; background: #f8f9fa; }
        .admin-header { background: linear-gradient(90deg, #388e3c 0%, #43a047 100%); color: #fff; padding: 1rem 0; box-shadow: 0 2px 8px rgba(60, 60, 60, 0.08); }
        .admin-header .navbar-brand { font-weight: bold; font-size: 1.5rem; color: #fff; }
        .admin-header .nav-link, .admin-header .nav-link:visited { color: #e0f2f1; font-size: 1.1rem; margin-left: 1rem; }
        .admin-header .nav-link.active, .admin-header .nav-link:hover { color: #ffd600; font-weight: bold; }
        .admin-footer { background: linear-gradient(90deg, #388e3c 0%, #43a047 100%); color: #fff; border-radius: 24px 24px 0 0; box-shadow: 0 -2px 16px rgba(60, 60, 60, 0.08); margin-top: 60px; padding: 1.5rem 0 1rem 0; text-align: center; }
        .page-title { font-weight: bold; color: #388e3c; margin-bottom: 2rem; }
        .table thead { background: #e8f5e9; color: #388e3c; }
        .btn-info, .btn-success { min-width: 90px; }
        .status-badge { font-size: 1rem; }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg admin-header">
        <div class="container-fluid">
            <a class="navbar-brand" href="dashboard.php"><i class="fas fa-seedling me-2"></i>پنل مدیریت گیاهان سبز</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNavbar" aria-controls="adminNavbar" aria-expanded="false" aria-label="تغییر منو">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="adminNavbar">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link" href="dashboard.php"><i class="fas fa-home"></i> داشبورد</a></li>
                    <li class="nav-item"><a class="nav-link" href="products.php"><i class="fas fa-leaf"></i> محصولات</a></li>
                    <li class="nav-item"><a class="nav-link" href="categories.php"><i class="fas fa-list"></i> دسته‌بندی‌ها</a></li>
                    <li class="nav-item"><a class="nav-link" href="users.php"><i class="fas fa-users"></i> کاربران</a></li>
                    <li class="nav-item"><a class="nav-link active" href="orders.php"><i class="fas fa-shopping-cart"></i> سفارشات</a></li>
                    <li class="nav-item"><a class="nav-link" href="messages.php"><i class="fas fa-envelope"></i> پیام‌ها</a></li>
                    <li class="nav-item"><a class="nav-link" href="../index.php"><i class="fas fa-store"></i> مشاهده سایت</a></li>
                    <li class="nav-item"><a class="nav-link" href="logout.php"><i class="fas fa-sign-out-alt"></i> خروج</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container py-5" data-aos="fade-up">
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
            <h2 class="page-title mb-0"><i class="fas fa-shopping-cart me-2"></i>مدیریت سفارشات</h2>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>کاربر</th>
                        <th>مبلغ کل (تومان)</th>
                        <th>تاریخ ثبت</th>
                        <th>وضعیت</th>
                        <th>عملیات</th>
                    </tr>
                </thead>
                <tbody>
                <?php if ($orders): $i=1; foreach ($orders as $order): ?>
                    <tr>
                        <td><?php echo $i++; ?></td>
                        <td><?php echo htmlspecialchars($order['user_name']); ?></td>
                        <td><?php echo number_format($order['total_amount']); ?></td>
                        <td><?php echo date('Y/m/d', strtotime($order['created_at'])); ?></td>
                        <td>
                            <span class="badge status-badge bg-<?php echo $order['status'] === 'pending' ? 'warning' : ($order['status'] === 'shipped' ? 'success' : 'secondary'); ?>">
                                <?php echo $order['status'] === 'pending' ? 'در حال پردازش' : ($order['status'] === 'shipped' ? 'ارسال شده' : 'نامشخص'); ?>
                            </span>
                        </td>
                        <td>
                            <a href="../invoice.php?order_id=<?php echo $order['id']; ?>" class="btn btn-info btn-sm"><i class="fas fa-eye"></i> مشاهده</a>
                            <?php if ($order['status'] === 'pending'): ?>
                            <form method="POST" action="orders.php" style="display:inline-block">
                                <input type="hidden" name="ship_order_id" value="<?php echo $order['id']; ?>">
                                <button type="submit" class="btn btn-success btn-sm"><i class="fas fa-check"></i> ارسال شد</button>
                            </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; else: ?>
                    <tr><td colspan="6">سفارشی یافت نشد.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <footer class="admin-footer mt-5">
        <div class="container">
            <h5>پنل مدیریت فروشگاه گیاهان سبز</h5>
            <p class="mb-0">&copy; 2024 Plant Shop Admin Panel</p>
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@next/dist/aos.js"></script>
    <script>AOS.init();</script>
</body>
</html> 
<?php
session_start();
require_once 'config/database.php';
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}
$user_id = $_SESSION['user_id'];
$orders = [];
$stmt = $conn->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $orders[] = $row;
}
$stmt->close();
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>سفارشات من | فروشگاه گیاهان سبز</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://cdn.jsdelivr.net/gh/rastikerdar/vazirmatn@v33.003/Vazirmatn-font-face.css" rel="stylesheet" type="text/css" />
    <style>
        .orders-table th, .orders-table td { text-align: center; vertical-align: middle; }
    </style>
</head>
<body>
<?php include 'partials/navbar.php'; ?>
    <div class="container py-5">
        <h2 class="mb-4 text-success text-center"><i class="fas fa-list me-2"></i>سفارشات من</h2>
        <div class="table-responsive">
            <table class="table table-bordered orders-table align-middle">
                <thead class="table-success">
                    <tr>
                        <th>#</th>
                        <th>شماره سفارش</th>
                        <th>تاریخ</th>
                        <th>جمع کل (تومان)</th>
                        <th>وضعیت</th>
                        <th>دانلود فاکتور</th>
                    </tr>
                </thead>
                <tbody>
                <?php if ($orders): $i=1; foreach ($orders as $order): ?>
                    <tr>
                        <td><?php echo $i++; ?></td>
                        <td><?php echo $order['id']; ?></td>
                        <td><?php echo date('Y/m/d', strtotime($order['created_at'])); ?></td>
                        <td><?php echo number_format($order['total_amount']); ?></td>
                        <td>
                            <span class="badge bg-<?php echo $order['status'] === 'pending' ? 'warning' : ($order['status'] === 'shipped' ? 'success' : 'secondary'); ?>">
                                <?php echo $order['status'] === 'pending' ? 'در حال پردازش' : ($order['status'] === 'shipped' ? 'ارسال شده' : 'نامشخص'); ?>
                            </span>
                        </td>
                        <td><a href="invoice.php?order_id=<?php echo $order['id']; ?>" class="btn btn-outline-success btn-sm"><i class="fas fa-file-pdf"></i> دانلود فاکتور</a></td>
                    </tr>
                <?php endforeach; else: ?>
                    <tr><td colspan="6">سفارشی یافت نشد.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php include 'partials/footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 
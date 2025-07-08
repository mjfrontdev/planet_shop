<?php
require_once 'config/database.php';
$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;
$order = null;
$order_items = [];
if ($order_id > 0) {
    $stmt = $conn->prepare("SELECT * FROM orders WHERE id = ?");
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $order = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    $stmt = $conn->prepare("SELECT oi.*, p.name as product_name FROM order_items oi JOIN products p ON oi.product_id = p.id WHERE oi.order_id = ?");
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $order_items = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>فاکتور خرید | فروشگاه گیاهان سبز</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://cdn.jsdelivr.net/gh/rastikerdar/vazirmatn@v33.003/Vazirmatn-font-face.css" rel="stylesheet" type="text/css" />
    <style>
        .invoice-box { background: #fff; border-radius: 16px; box-shadow: 0 4px 16px rgba(60,60,60,0.08); padding: 2rem; margin-top: 2rem; }
        .invoice-header { border-bottom: 2px solid #43a047; margin-bottom: 1.5rem; padding-bottom: 1rem; }
        .invoice-table th, .invoice-table td { text-align: center; vertical-align: middle; }
    </style>
</head>
<body>
<?php include 'partials/navbar.php'; ?>
    <div class="container">
        <div class="invoice-box mx-auto" style="max-width: 700px;">
            <div class="invoice-header d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-1 text-success">فاکتور خرید</h4>
                    <div>شماره سفارش: <span class="fw-bold"><?php echo $order ? $order['id'] : '-'; ?></span></div>
                    <div>تاریخ: <span class="fw-bold"><?php echo $order ? date('Y/m/d', strtotime($order['created_at'])) : '-'; ?></span></div>
                </div>
                <div>
                    <img src="assets/img/logo.png" alt="لوگو" style="height: 60px;">
                </div>
            </div>
            <div class="mb-3">
                <strong>مشخصات خریدار:</strong><br>
                نام: <?php echo $order ? htmlspecialchars($order['name']) : '-'; ?><br>
                شماره تماس: <?php echo $order ? htmlspecialchars($order['phone']) : '-'; ?><br>
                آدرس: <?php echo $order ? nl2br(htmlspecialchars($order['address'])) : '-'; ?>
            </div>
            <table class="table table-bordered invoice-table mb-4">
                <thead class="table-success">
                    <tr>
                        <th>#</th>
                        <th>نام محصول</th>
                        <th>تعداد</th>
                        <th>قیمت واحد</th>
                        <th>جمع</th>
                    </tr>
                </thead>
                <tbody>
                <?php if ($order_items): $i=1; foreach ($order_items as $item): ?>
                    <tr>
                        <td><?php echo $i++; ?></td>
                        <td><?php echo htmlspecialchars($item['product_name']); ?></td>
                        <td><?php echo $item['quantity']; ?></td>
                        <td><?php echo number_format($item['price']); ?></td>
                        <td><?php echo number_format($item['price'] * $item['quantity']); ?></td>
                    </tr>
                <?php endforeach; else: ?>
                    <tr><td colspan="5">موردی یافت نشد.</td></tr>
                <?php endif; ?>
                </tbody>
                <tfoot>
                    <tr class="table-success">
                        <th colspan="4" class="text-end">جمع کل</th>
                        <th><?php echo $order ? number_format($order['total_amount']) . ' تومان' : '-'; ?></th>
                    </tr>
                </tfoot>
            </table>
            <div class="text-center">
                <a href="#" class="btn btn-success"><i class="fas fa-file-pdf me-2"></i>دانلود فاکتور PDF</a>
                <a href="user-orders.php" class="btn btn-outline-success ms-2"><i class="fas fa-list me-2"></i>مشاهده سفارش</a>
            </div>
        </div>
    </div>
<?php include 'partials/footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 
<?php
session_start();
require_once 'config/database.php';
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    header('Location: cart.php');
    exit();
}
// دریافت اطلاعات کاربر
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$cart = $_SESSION['cart'];
$total = 0;
foreach ($cart as $item) $total += $item['price'] * $item['qty'];
// ثبت سفارش
$success = false;
$order_id = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['checkout'])) {
    $name = trim($_POST['name']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);
    $conn->begin_transaction();
    $stmt = $conn->prepare("INSERT INTO orders (user_id, address, name, phone, total_amount, status) VALUES (?, ?, ?, ?, ?, 'pending')");
    $stmt->bind_param("isssd", $_SESSION['user_id'], $address, $name, $phone, $total);
    if ($stmt->execute()) {
        $order_id = $conn->insert_id;
        $ok = true;
        foreach ($cart as $item) {
            $stmt2 = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
            $stmt2->bind_param("iiid", $order_id, $item['id'], $item['qty'], $item['price']);
            if (!$stmt2->execute()) $ok = false;
        }
        if ($ok) {
            $conn->commit();
            $_SESSION['cart'] = [];
            header('Location: invoice.php?order_id=' . $order_id);
            exit();
        } else {
            $conn->rollback();
        }
    } else {
        $conn->rollback();
    }
}
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>پرداخت و ثبت سفارش | فروشگاه گیاهان سبز</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://cdn.jsdelivr.net/gh/rastikerdar/vazirmatn@v33.003/Vazirmatn-font-face.css" rel="stylesheet" type="text/css" />
    <style>
        .order-summary { background: #e8f5e9; border-radius: 12px; padding: 1.5rem; }
    </style>
</head>
<body>
<?php include 'partials/navbar.php'; ?>
    <div class="container py-5">
        <h2 class="mb-4 text-success text-center"><i class="fas fa-credit-card me-2"></i>پرداخت و ثبت سفارش</h2>
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="order-summary mb-4">
                    <h5>خلاصه سفارش:</h5>
                    <ul class="list-group mb-3">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            گیاه آپارتمانی فیکوس <span>۱ × ۲۵۰,۰۰۰</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            گیاه دارویی آلوئه ورا <span>۲ × ۱۸۰,۰۰۰</span>
                        </li>
                        <li class="list-group-item list-group-item-success d-flex justify-content-between align-items-center fw-bold">
                            جمع کل <span>۶۱۰,۰۰۰ تومان</span>
                        </li>
                    </ul>
                </div>
                <form method="POST" action="checkout.php">
                    <div class="mb-3">
                        <label class="form-label">نام و نام خانوادگی</label>
                        <input type="text" class="form-control" name="name" required value="<?= htmlspecialchars($user['name']) ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">شماره تماس</label>
                        <input type="text" class="form-control" name="phone" required value="<?= htmlspecialchars($user['phone']) ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">آدرس ارسال</label>
                        <textarea class="form-control" name="address" rows="3" required><?= htmlspecialchars($user['address'] ?? '') ?></textarea>
                    </div>
                    <button type="submit" name="checkout" class="btn btn-lg btn-success w-100"><i class="fas fa-check me-2"></i>تایید و پرداخت</button>
                </form>
            </div>
        </div>
    </div>
<?php include 'partials/footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 
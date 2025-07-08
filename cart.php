<?php
session_start();
require_once 'config/database.php';
if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];
$cart = $_SESSION['cart'];
// حذف محصول از سبد
if (isset($_GET['remove'])) {
    $id = $_GET['remove'];
    unset($_SESSION['cart'][$id]);
    header('Location: cart.php?removed=1');
    exit();
}
// ویرایش تعداد
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_qty'])) {
    foreach ($_POST['qty'] as $id => $qty) {
        if (isset($_SESSION['cart'][$id])) {
            $_SESSION['cart'][$id]['qty'] = max(1, intval($qty));
        }
    }
    header('Location: cart.php');
    exit();
}
$cart = $_SESSION['cart'];
$total = 0;
foreach ($cart as $item) $total += $item['price'] * $item['qty'];
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>سبد خرید | فروشگاه گیاهان سبز</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://cdn.jsdelivr.net/gh/rastikerdar/vazirmatn@v33.003/Vazirmatn-font-face.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.12/dist/sweetalert2.min.css" />
    <style>
        .cart-table th, .cart-table td { vertical-align: middle; text-align: center; }
        .cart-img-thumb { width: 60px; height: 60px; object-fit: cover; border-radius: 8px; }
        .discount-box { max-width: 300px; margin: 0 auto; }
        .cart-summary { background: #e8f5e9; border-radius: 12px; padding: 1.5rem; }
    </style>
</head>
<body>
<?php include 'partials/navbar.php'; ?>
    <div class="container py-5">
        <h2 class="mb-4 text-success text-center"><i class="fas fa-shopping-cart me-2"></i>سبد خرید شما</h2>
        <?php if (empty($cart)): ?>
            <div class="alert alert-info text-center">سبد خرید شما خالی است.</div>
        <?php else: ?>
        <form method="POST" action="cart.php">
        <div class="table-responsive mb-4">
            <table class="table table-bordered cart-table align-middle">
                <thead class="table-success">
                    <tr>
                        <th>#</th>
                        <th>تصویر</th>
                        <th>نام محصول</th>
                        <th>قیمت (تومان)</th>
                        <th>تعداد</th>
                        <th>جمع</th>
                        <th>حذف</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i=1; foreach ($cart as $id => $item): ?>
                    <tr>
                        <td><?= $i++ ?></td>
                        <td><img src="<?= htmlspecialchars($item['image']) ?>" class="cart-img-thumb" alt="<?= htmlspecialchars($item['name']) ?>"></td>
                        <td><?= htmlspecialchars($item['name']) ?></td>
                        <td><?= number_format($item['price']) ?></td>
                        <td>
                            <div class="input-group justify-content-center">
                                <button type="button" class="btn btn-outline-success btn-sm qty-plus" data-id="<?= $id ?>">+</button>
                                <input type="text" name="qty[<?= $id ?>]" class="form-control text-center" value="<?= $item['qty'] ?>" style="max-width: 50px;" readonly>
                                <button type="button" class="btn btn-outline-danger btn-sm qty-minus" data-id="<?= $id ?>">-</button>
                            </div>
                        </td>
                        <td><?= number_format($item['price'] * $item['qty']) ?></td>
                        <td><button type="button" class="btn btn-danger btn-sm btn-remove" data-id="<?= $id ?>"><i class="fas fa-trash"></i></button></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="row justify-content-center mb-4">
            <div class="col-md-6">
                <form class="discount-box d-flex align-items-center gap-2">
                    <input type="text" class="form-control" placeholder="کد تخفیف">
                    <button class="btn btn-success" type="button">اعمال</button>
                </form>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="cart-summary text-center">
                    <h5>جمع کل سبد خرید:</h5>
                    <div class="fs-3 fw-bold text-success mb-3"><?= number_format($total) ?> تومان</div>
                    <button type="submit" name="update_qty" class="btn btn-outline-success mb-2 w-100">بروزرسانی تعداد</button>
                    <a href="checkout.php" class="btn btn-lg btn-success w-100 mt-2" id="checkout-btn"><i class="fas fa-credit-card me-2"></i>پرداخت و ثبت سفارش</a>
                </div>
            </div>
        </div>
        </form>
        <?php endif; ?>
    </div>
<?php include 'partials/footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.12/dist/sweetalert2.all.min.js"></script>
<script>
// حذف محصول با SweetAlert2
const removeBtns = document.querySelectorAll('.btn-remove');
removeBtns.forEach(btn => {
    btn.addEventListener('click', function() {
        const id = this.getAttribute('data-id');
        Swal.fire({
            title: 'حذف محصول',
            text: 'آیا از حذف این محصول مطمئن هستید؟',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'بله، حذف کن',
            cancelButtonText: 'انصراف',
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
        }).then((result) => {
            if (result.isConfirmed) {
                window.location = 'cart.php?remove=' + id;
            }
        });
    });
});
// نمایش پیام حذف موفق
<?php if (isset($_GET['removed'])): ?>
Swal.fire({
    icon: 'success',
    title: 'محصول حذف شد!',
    showConfirmButton: false,
    timer: 1500
});
<?php endif; ?>
// افزایش/کاهش تعداد (فقط ظاهر، نیاز به AJAX برای واقعی شدن)
document.querySelectorAll('.qty-plus').forEach(btn => {
    btn.addEventListener('click', function() {
        const input = this.parentElement.querySelector('input');
        input.value = parseInt(input.value) + 1;
    });
});
document.querySelectorAll('.qty-minus').forEach(btn => {
    btn.addEventListener('click', function() {
        const input = this.parentElement.querySelector('input');
        if (parseInt(input.value) > 1) input.value = parseInt(input.value) - 1;
    });
});
</script>
</body>
</html> 
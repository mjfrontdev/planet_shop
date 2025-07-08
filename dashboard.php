<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

require_once 'config/database.php';

// Get user information
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

// Get user's orders
$stmt = $conn->prepare("
    SELECT o.*, GROUP_CONCAT(p.name) as products
    FROM orders o
    JOIN order_items oi ON o.id = oi.order_id
    JOIN products p ON oi.product_id = p.id
    WHERE o.user_id = ?
    GROUP BY o.id
    ORDER BY o.created_at DESC
    LIMIT 5
");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$orders = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    
    $errors = [];
    
    // Validate current password
    if (!empty($current_password)) {
        if (!password_verify($current_password, $user['password'])) {
            $errors[] = 'رمز عبور فعلی اشتباه است';
        }
    }
    
    // Check if phone is already taken by another user
    if ($phone !== $user['phone']) {
        $stmt = $conn->prepare("SELECT id FROM users WHERE phone = ? AND id != ?");
        $stmt->bind_param("si", $phone, $_SESSION['user_id']);
        $stmt->execute();
        if ($stmt->get_result()->num_rows > 0) {
            $errors[] = 'این شماره موبایل قبلاً ثبت شده است';
        }
    }
    
    if (empty($errors)) {
        if (!empty($new_password)) {
            // Update with new password
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE users SET name = ?, phone = ?, password = ? WHERE id = ?");
            $stmt->bind_param("sssi", $name, $phone, $hashed_password, $_SESSION['user_id']);
        } else {
            // Update without changing password
            $stmt = $conn->prepare("UPDATE users SET name = ?, phone = ? WHERE id = ?");
            $stmt->bind_param("ssi", $name, $phone, $_SESSION['user_id']);
        }
        
        if ($stmt->execute()) {
            $success = 'اطلاعات با موفقیت بروزرسانی شد';
            // Refresh user data
            $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
            $stmt->bind_param("i", $_SESSION['user_id']);
            $stmt->execute();
            $user = $stmt->get_result()->fetch_assoc();
        } else {
            $errors[] = 'خطا در بروزرسانی اطلاعات';
        }
    }
}

// Get cart from session
$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>پنل کاربری | فروشگاه گیاهان</title>
    
    <!-- Bootstrap RTL CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
    
    <!-- Vazirmatn Font -->
    <link href="https://cdn.jsdelivr.net/gh/rastikerdar/vazirmatn@v33.003/Vazirmatn-font-face.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
</head>
<body>
    <div id="global-loader"><div class="spinner"></div></div>
    <?php include 'partials/navbar.php'; ?>
    <div class="container py-5">
        <div class="row">
            <!-- سایدبار -->
            <div class="col-md-3 mb-4">
                <div class="user-sidebar">
                    <ul class="list-group">
                        <li class="list-group-item active"><i class="fa fa-home me-2"></i>خانه</li>
                        <li class="list-group-item"><a href="categories.php" class="text-decoration-none text-reset"><i class="fa fa-th-large me-2"></i>دسته‌بندی‌ها</a></li>
                        <li class="list-group-item"><a href="products.php" class="text-decoration-none text-reset"><i class="fa fa-leaf me-2"></i>محصولات</a></li>
                        <li class="list-group-item"><a href="cart.php" class="text-decoration-none text-reset"><i class="fa fa-shopping-cart me-2"></i>سبد خرید</a></li>
                        <li class="list-group-item"><a href="#orders" class="text-decoration-none text-reset"><i class="fa fa-receipt me-2"></i>سفارشات من</a></li>
                        <li class="list-group-item"><a href="#profile" class="text-decoration-none text-reset"><i class="fa fa-user-edit me-2"></i>ویرایش پروفایل</a></li>
                        <li class="list-group-item"><a href="#tickets" class="text-decoration-none text-reset"><i class="fa fa-envelope me-2"></i>تیکت‌ها</a></li>
                        <li class="list-group-item"><a href="logout.php" class="text-decoration-none text-danger"><i class="fa fa-sign-out-alt me-2"></i>خروج</a></li>
                    </ul>
                </div>
            </div>
            <!-- محتوای اصلی -->
            <div class="col-md-9">
                <?php if (isset($_SESSION['ticket_success'])): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fa fa-check-circle me-2"></i><?php echo $_SESSION['ticket_success']; unset($_SESSION['ticket_success']); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="بستن"></button>
                    </div>
                <?php elseif (isset($_SESSION['ticket_error'])): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fa fa-exclamation-triangle me-2"></i><?php echo $_SESSION['ticket_error']; unset($_SESSION['ticket_error']); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="بستن"></button>
                    </div>
                <?php endif; ?>
                <section class="mb-5" data-aos="fade-up">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="card shadow border-0 h-100">
                                <div class="card-body">
                                    <h5 class="card-title mb-3"><i class="fa fa-user-circle text-success me-2"></i>خوش آمدید، <?php echo htmlspecialchars($user['name']); ?></h5>
                                    <p class="mb-1"><i class="fa fa-envelope me-2"></i><?php echo htmlspecialchars($user['email']); ?></p>
                                    <p class="mb-1"><i class="fa fa-phone me-2"></i><?php echo htmlspecialchars($user['phone']); ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card shadow border-0 h-100">
                                <div class="card-body">
                                    <h5 class="card-title mb-3"><i class="fa fa-shopping-cart text-success me-2"></i>خلاصه سبد خرید</h5>
                                    <?php if (empty($cart)): ?>
                                        <p class="text-muted">سبد خرید شما خالی است.</p>
                                    <?php else:
                                        $total = 0;
                                        foreach ($cart as $item) $total += $item['price'] * $item['qty'];
                                    ?>
                                        <ul class="list-unstyled mb-2">
                                            <?php foreach ($cart as $item): ?>
                                                <li><i class="fa fa-leaf text-success"></i> <?php echo htmlspecialchars($item['name']); ?> × <?php echo $item['qty']; ?></li>
                                            <?php endforeach; ?>
                                        </ul>
                                        <div class="fw-bold">جمع کل: <?php echo number_format($total); ?> تومان</div>
                                        <a href="cart.php" class="btn btn-outline-success btn-sm mt-2">مشاهده سبد خرید</a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <!-- سفارشات اخیر -->
                <section id="orders" class="mb-5" data-aos="fade-up">
                    <div class="card shadow border-0">
                        <div class="card-body">
                            <h5 class="card-title mb-3"><i class="fa fa-receipt text-success me-2"></i>سفارشات اخیر</h5>
                            <?php if (empty($orders)): ?>
                                <p class="text-muted">شما هنوز سفارشی ثبت نکرده‌اید.</p>
                            <?php else: ?>
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>شماره سفارش</th>
                                                <th>تاریخ</th>
                                                <th>محصولات</th>
                                                <th>مبلغ کل</th>
                                                <th>وضعیت</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($orders as $order): ?>
                                                <tr>
                                                    <td>#<?php echo $order['id']; ?></td>
                                                    <td><?php echo date('Y/m/d', strtotime($order['created_at'])); ?></td>
                                                    <td><?php echo htmlspecialchars($order['products']); ?></td>
                                                    <td><?php echo number_format($order['total_amount']); ?> تومان</td>
                                                    <td>
                                                        <span class="badge bg-<?php echo $order['status'] === 'pending' ? 'warning' : 'success'; ?>">
                                                            <?php echo $order['status'] === 'pending' ? 'در انتظار پرداخت' : 'تکمیل شده'; ?>
                                                        </span>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </section>
                <!-- ویرایش پروفایل -->
                <section id="profile" data-aos="fade-up">
                    <div class="card shadow border-0">
                        <div class="card-body">
                            <h5 class="card-title mb-3"><i class="fa fa-user-edit text-success me-2"></i>ویرایش پروفایل</h5>
                            <?php if (isset($success)): ?>
                                <div class="alert alert-success"><?php echo $success; ?></div>
                            <?php endif; ?>
                            
                            <?php if (!empty($errors)): ?>
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        <?php foreach ($errors as $error): ?>
                                            <li><?php echo $error; ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            <?php endif; ?>
                            
                            <form method="POST" action="" id="profile-form">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="name" class="form-label">نام و نام خانوادگی</label>
                                        <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="email" class="form-label">ایمیل</label>
                                        <input type="email" class="form-control" value="<?php echo htmlspecialchars($user['email']); ?>" readonly>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="phone" class="form-label">شماره موبایل</label>
                                        <input type="tel" class="form-control" id="phone" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="current_password" class="form-label">رمز عبور فعلی</label>
                                        <input type="password" class="form-control" id="current_password" name="current_password">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="new_password" class="form-label">رمز عبور جدید</label>
                                        <input type="password" class="form-control" id="new_password" name="new_password">
                                    </div>
                                </div>
                                <button type="submit" name="update_profile" class="btn btn-success mt-3" id="profile-btn">
                                    <span>بروزرسانی اطلاعات</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </section>
                <!-- بخش نکات طلایی با بک‌گراند گیاهی و آیکون -->
                <section class="mb-5" data-aos="fade-up">
                    <div class="card shadow border-0 p-4 d-flex flex-row align-items-center" style="background: url('assets/images/kendal-TW2bfT_tWDI-unsplash.jpg') center right/cover no-repeat, linear-gradient(90deg, #e8f5e9 60%, #c8e6c9 100%); min-height:180px; border-radius: 24px;">
                        <div class="flex-grow-1">
                            <h3 class="fw-bold mb-3" style="color:#388e3c;"><i class="fa fa-lightbulb text-warning me-2"></i>نکات طلایی نگهداری گیاهان آپارتمانی</h3>
                            <ul class="mb-0" style="font-size:1.1rem; color:#333;">
                                <li>نور مناسب و غیرمستقیم برای بیشتر گیاهان ضروری است.</li>
                                <li>آبیاری منظم اما بدون غرقاب کردن خاک انجام دهید.</li>
                                <li>برگ‌های گیاه را هر چند وقت یکبار با دستمال مرطوب، تمیز کنید.</li>
                                <li>کوددهی ماهانه به رشد بهتر گیاه کمک می‌کند.</li>
                            </ul>
                        </div>
                        <div class="d-none d-md-block ms-4">
                            <img src="assets/images/pexels-iriser-1005715.jpg" alt="نکات نگهداری گیاهان" style="width:110px; border-radius:18px; box-shadow:0 2px 12px #c8e6c9;">
                        </div>
                    </div>
                </section>
                <!-- بخش تیکت‌های کاربر -->
                <section id="tickets" class="mb-5" data-aos="fade-up">
                    <div class="card shadow border-0">
                        <div class="card-body">
                            <h5 class="card-title mb-3"><i class="fa fa-envelope text-success me-2"></i>تیکت‌های من</h5>
                            <form method="POST" action="user_tickets.php" class="mb-4">
                                <div class="row g-2 align-items-end">
                                    <div class="col-md-4">
                                        <label class="form-label">موضوع تیکت</label>
                                        <input type="text" name="subject" class="form-control" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">متن پیام</label>
                                        <textarea name="message" class="form-control" rows="2" required></textarea>
                                    </div>
                                    <div class="col-md-2">
                                        <button type="submit" class="btn btn-success w-100"><i class="fa fa-paper-plane me-1"></i> ارسال تیکت</button>
                                    </div>
                                </div>
                            </form>
                            <?php
                            // نمایش لیست تیکت‌های کاربر
                            require_once 'config/database.php';
                            $user_id = $_SESSION['user_id'];
                            $tickets = $conn->query("SELECT * FROM tickets WHERE user_id = $user_id ORDER BY created_at DESC");
                            if ($tickets && $tickets->num_rows > 0):
                            ?>
                            <div class="table-responsive">
                                <table class="table table-bordered align-middle">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>موضوع</th>
                                            <th>وضعیت</th>
                                            <th>پاسخ ادمین</th>
                                            <th>تاریخ</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php $i=1; while($ticket = $tickets->fetch_assoc()): ?>
                                        <tr>
                                            <td><?php echo $i++; ?></td>
                                            <td><?php echo htmlspecialchars($ticket['subject']); ?></td>
                                            <td><span class="badge bg-<?php echo $ticket['status']=='open'?'warning':($ticket['status']=='answered'?'info':'secondary'); ?>"><?php echo $ticket['status']=='open'?'باز':($ticket['status']=='answered'?'پاسخ داده شده':'بسته'); ?></span></td>
                                            <td><?php echo $ticket['admin_reply'] ? '<span class="text-success">'.htmlspecialchars($ticket['admin_reply']).'</span>' : '<span class="text-muted">---</span>'; ?></td>
                                            <td><?php echo date('Y/m/d', strtotime($ticket['created_at'])); ?></td>
                                        </tr>
                                    <?php endwhile; ?>
                                    </tbody>
                                </table>
                            </div>
                            <?php else: ?>
                                <div class="alert alert-info">تیکتی ثبت نشده است.</div>
                            <?php endif; ?>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
    <?php include 'partials/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@next/dist/aos.js"></script>
    <script src="assets/js/main.js"></script>
    <script>AOS.init();</script>
    <script>
    window.addEventListener('load', function() {
      document.getElementById('global-loader').style.display = 'none';
    });
    document.getElementById('profile-form').addEventListener('submit', function(e) {
        var btn = document.getElementById('profile-btn');
        btn.classList.add('btn-loading');
        btn.innerHTML = '<span>در حال بروزرسانی...</span> <span class="spinner-border spinner-border-sm"></span>';
    });
    </script>
</body>
</html> 
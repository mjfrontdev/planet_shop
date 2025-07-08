<?php
require_once '../config/database.php';

// پیام موفقیت/خطا
$alert = null;
$alert_type = 'success';

// تغییر وضعیت تیکت به بسته
if (isset($_POST['close_id'])) {
    $id = intval($_POST['close_id']);
    if ($conn->query("UPDATE tickets SET status='closed' WHERE id=$id")) {
        $alert = 'تیکت با موفقیت بسته شد.';
        $alert_type = 'success';
    } else {
        $alert = 'خطا در بستن تیکت!';
        $alert_type = 'error';
    }
}
// حذف تیکت
if (isset($_POST['delete_id'])) {
    $id = intval($_POST['delete_id']);
    if ($conn->query("DELETE FROM tickets WHERE id=$id")) {
        $alert = 'تیکت با موفقیت حذف شد.';
        $alert_type = 'success';
    } else {
        $alert = 'خطا در حذف تیکت!';
        $alert_type = 'error';
    }
}
// پاسخ ادمین
if (isset($_POST['reply_id']) && isset($_POST['admin_reply'])) {
    $id = intval($_POST['reply_id']);
    $reply = trim($_POST['admin_reply']);
    if ($conn->query("UPDATE tickets SET admin_reply='" . $conn->real_escape_string($reply) . "', status='answered' WHERE id=$id")) {
        $alert = 'پاسخ با موفقیت ارسال شد.';
        $alert_type = 'success';
    } else {
        $alert = 'خطا در ارسال پاسخ!';
        $alert_type = 'error';
    }
}
$tickets = $conn->query("SELECT t.*, u.name, u.email FROM tickets t JOIN users u ON t.user_id = u.id ORDER BY t.created_at DESC");
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>مدیریت تیکت‌ها | پنل ادمین فروشگاه گیاهان</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
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

        .admin-footer {
            background: linear-gradient(90deg, #388e3c 0%, #43a047 100%);
            color: #fff;
            border-radius: 24px 24px 0 0;
            box-shadow: 0 -2px 16px rgba(60, 60, 60, 0.08);
            margin-top: 60px;
            padding: 1.5rem 0 1rem 0;
            text-align: center;
        }

        .page-title {
            font-weight: bold;
            color: #388e3c;
            margin-bottom: 2rem;
        }

        .table thead {
            background: #e8f5e9;
            color: #388e3c;
        }

        .badge-status {
            font-size: 1rem;
        }

        .ticket-reply-box {
            background: #e8f5e9;
            border-radius: 12px;
            padding: 1rem;
            margin-top: 1rem;
        }

        .ticket-message {
            background: #fff;
            border-radius: 12px;
            padding: 1rem;
            margin-bottom: 0.5rem;
        }
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
                    <li class="nav-item"><a class="nav-link" href="orders.php"><i class="fas fa-shopping-cart"></i> سفارشات</a></li>
                    <li class="nav-item"><a class="nav-link active" href="tickets.php"><i class="fas fa-ticket-alt"></i> تیکت‌ها</a></li>
                    <li class="nav-item"><a class="nav-link" href="../index.php"><i class="fas fa-store"></i> مشاهده سایت</a></li>
                    <li class="nav-item"><a class="nav-link" href="logout.php"><i class="fas fa-sign-out-alt"></i> خروج</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container py-5" data-aos="fade-up">
        <?php if ($alert): ?>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: '<?php echo $alert_type; ?>',
                        title: '<?php echo $alert_type === 'success' ? 'موفق!' : 'خطا!'; ?>',
                        text: '<?php echo $alert; ?>',
                        confirmButtonText: 'باشه'
                    });
                });
            </script>
        <?php endif; ?>
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
            <h2 class="page-title mb-0"><i class="fas fa-ticket-alt me-2"></i>مدیریت تیکت‌ها</h2>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>کاربر</th>
                        <th>ایمیل</th>
                        <th>موضوع</th>
                        <th>وضعیت</th>
                        <th>تاریخ</th>
                        <th>عملیات</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($tickets && $tickets->num_rows > 0): $i = 1;
                        while ($ticket = $tickets->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $i++; ?></td>
                                <td><?php echo htmlspecialchars($ticket['name']); ?></td>
                                <td><?php echo htmlspecialchars($ticket['email']); ?></td>
                                <td><?php echo htmlspecialchars($ticket['subject']); ?></td>
                                <td>
                                    <span class="badge bg-<?php echo $ticket['status'] == 'open' ? 'warning' : ($ticket['status'] == 'answered' ? 'info' : 'secondary'); ?> badge-status">
                                        <?php echo $ticket['status'] == 'open' ? 'باز' : ($ticket['status'] == 'answered' ? 'پاسخ داده شده' : 'بسته'); ?>
                                    </span>
                                </td>
                                <td><?php echo date('Y/m/d', strtotime($ticket['created_at'])); ?></td>
                                <td>
                                    <button class="btn btn-info btn-sm view-ticket-btn" data-id="<?php echo $ticket['id']; ?>"><i class="fas fa-eye"></i> مشاهده</button>
                                    <?php if ($ticket['status'] != 'closed'): ?>
                                        <form method="POST" action="messages.php" class="d-inline">
                                            <input type="hidden" name="close_id" value="<?php echo $ticket['id']; ?>">
                                            <button type="submit" class="btn btn-secondary btn-sm"><i class="fas fa-lock"></i> بستن</button>
                                        </form>
                                    <?php endif; ?>
                                    <form method="POST" action="messages.php" class="d-inline delete-form">
                                        <input type="hidden" name="delete_id" value="<?php echo $ticket['id']; ?>">
                                        <button type="submit" class="btn btn-danger btn-sm delete-btn"><i class="fas fa-trash"></i> حذف</button>
                                    </form>
                                </td>
                            </tr>
                            <tr class="ticket-details-row" id="ticket-details-<?php echo $ticket['id']; ?>" style="display:none;">
                                <td colspan="7">
                                    <div class="ticket-message mb-2"><strong>پیام کاربر:</strong><br><?php echo nl2br(htmlspecialchars($ticket['message'])); ?></div>
                                    <?php if ($ticket['admin_reply']): ?>
                                        <div class="ticket-reply-box"><strong>پاسخ ادمین:</strong><br><?php echo nl2br(htmlspecialchars($ticket['admin_reply'])); ?></div>
                                    <?php elseif ($ticket['status'] != 'closed'): ?>
                                        <form method="POST" action="messages.php" class="ticket-reply-form mt-3">
                                            <input type="hidden" name="reply_id" value="<?php echo $ticket['id']; ?>">
                                            <div class="mb-2">
                                                <textarea name="admin_reply" class="form-control" rows="3" placeholder="پاسخ ادمین ..." required></textarea>
                                            </div>
                                            <button type="submit" class="btn btn-success"><i class="fas fa-reply"></i> ارسال پاسخ</button>
                                        </form>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endwhile;
                    else: ?>
                        <tr>
                            <td colspan="7" class="text-center">تیکتی یافت نشد.</td>
                        </tr>
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.12/dist/sweetalert2.all.min.js"></script>
    <script>
        AOS.init();
    </script>
    <script>
        // نمایش جزئیات تیکت
        document.querySelectorAll('.view-ticket-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const row = document.getElementById('ticket-details-' + id);
                if (row.style.display === 'none') {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
        // حذف با SweetAlert2
        document.querySelectorAll('.delete-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'حذف تیکت',
                    text: 'آیا از حذف این تیکت مطمئن هستید؟',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'بله، حذف کن!',
                    cancelButtonText: 'انصراف'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    </script>
</body>

</html>
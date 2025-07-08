<?php
require_once '../config/database.php';
// حذف دسته‌بندی
if (isset($_POST['delete_id'])) {
    $id = intval($_POST['delete_id']);
    $conn->query("DELETE FROM categories WHERE id=$id");
    header('Location: categories.php');
    exit();
}
$categories = $conn->query("SELECT * FROM categories ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>مدیریت دسته‌بندی‌ها | پنل ادمین فروشگاه گیاهان</title>
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
        .btn-success, .btn-danger, .btn-warning { min-width: 90px; }
        .add-category-btn { float: left; }
        @media (max-width: 768px) { .add-category-btn { float: none; width: 100%; margin-bottom: 1rem; } }
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
                    <li class="nav-item"><a class="nav-link active" href="categories.php"><i class="fas fa-list"></i> دسته‌بندی‌ها</a></li>
                    <li class="nav-item"><a class="nav-link" href="users.php"><i class="fas fa-users"></i> کاربران</a></li>
                    <li class="nav-item"><a class="nav-link" href="orders.php"><i class="fas fa-shopping-cart"></i> سفارشات</a></li>
                    <li class="nav-item"><a class="nav-link" href="messages.php"><i class="fas fa-envelope"></i> پیام‌ها</a></li>
                    <li class="nav-item"><a class="nav-link" href="../index.php"><i class="fas fa-store"></i> مشاهده سایت</a></li>
                    <li class="nav-item"><a class="nav-link" href="logout.php"><i class="fas fa-sign-out-alt"></i> خروج</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container py-5" data-aos="fade-up">
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
            <h2 class="page-title mb-0"><i class="fas fa-list me-2"></i>مدیریت دسته‌بندی‌ها</h2>
            <button class="btn btn-success add-category-btn"><i class="fas fa-plus"></i> افزودن دسته‌بندی جدید</button>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>نام دسته‌بندی</th>
                        <th>توضیحات</th>
                        <th>عملیات</th>
                    </tr>
                </thead>
                <tbody>
<?php if ($categories && $categories->num_rows > 0): $i=1; while($cat = $categories->fetch_assoc()): ?>
<tr>
    <td><?php echo $i++; ?></td>
    <td><?php echo htmlspecialchars($cat['name']); ?></td>
    <td><?php echo htmlspecialchars($cat['description']); ?></td>
    <td>
        <a href="edit_category.php?id=<?php echo $cat['id']; ?>" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i> ویرایش</a>
        <form method="POST" action="categories.php" class="d-inline delete-form">
            <input type="hidden" name="delete_id" value="<?php echo $cat['id']; ?>">
            <button type="submit" class="btn btn-danger btn-sm delete-btn"><i class="fas fa-trash"></i> حذف</button>
        </form>
    </td>
</tr>
<?php endwhile; else: ?>
<tr><td colspan="4" class="text-center">دسته‌بندی‌ای یافت نشد.</td></tr>
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.12/dist/sweetalert2.all.min.js"></script>
    <script>
document.querySelectorAll('.delete-form').forEach(form => {
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        Swal.fire({
            title: 'حذف دسته‌بندی',
            text: 'آیا از حذف این دسته‌بندی مطمئن هستید؟',
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
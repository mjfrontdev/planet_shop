<?php
require_once '../config/database.php';
$id = intval($_GET['id'] ?? 0);
$user = $conn->query("SELECT * FROM users WHERE id=$id")->fetch_assoc();
$success = $error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    if ($name && $email && $phone) {
        $stmt = $conn->prepare("UPDATE users SET name=?, email=?, phone=? WHERE id=?");
        $stmt->bind_param('sssi', $name, $email, $phone, $id);
        if ($stmt->execute()) {
            $success = 'اطلاعات کاربر با موفقیت بروزرسانی شد.';
            $user = $conn->query("SELECT * FROM users WHERE id=$id")->fetch_assoc();
        } else {
            $error = 'خطا در بروزرسانی اطلاعات.';
        }
        $stmt->close();
    } else {
        $error = 'همه فیلدها الزامی است.';
    }
}
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ویرایش کاربر | پنل مدیریت</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link href="https://cdn.jsdelivr.net/gh/rastikerdar/vazirmatn@v33.003/Vazirmatn-font-face.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
    <style>
        body { background: #f7faf7; }
        .edit-user-card {
            background: linear-gradient(120deg, #e8f5e9 60%, #c8e6c9 100%);
            border-radius: 24px;
            box-shadow: 0 2px 16px rgba(67,160,71,0.10);
            padding: 2.5rem 2rem;
            max-width: 500px;
            margin: 2rem auto;
        }
        .edit-user-card label { color: #388e3c; font-weight: 500; }
        .edit-user-card .form-control { border-radius: 12px; }
        .edit-user-card .btn-success { border-radius: 12px; font-weight: bold; }
        .edit-user-card .btn-secondary { border-radius: 12px; }
        @media (max-width: 600px) {
            .edit-user-card { padding: 1.2rem 0.5rem; }
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="edit-user-card shadow" data-aos="fade-up">
            <h3 class="mb-4 text-center" style="color:#388e3c;"><i class="fa fa-user-edit me-2"></i>ویرایش کاربر</h3>
            <?php if ($success): ?>
                <div class="alert alert-success"><i class="fa fa-check-circle me-2"></i><?php echo $success; ?></div>
            <?php elseif ($error): ?>
                <div class="alert alert-danger"><i class="fa fa-exclamation-triangle me-2"></i><?php echo $error; ?></div>
            <?php endif; ?>
            <form method="POST" autocomplete="off">
                <div class="mb-3">
                    <label>نام و نام خانوادگی</label>
                    <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($user['name']); ?>" required>
                </div>
                <div class="mb-3">
                    <label>ایمیل</label>
                    <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                </div>
                <div class="mb-3">
                    <label>شماره موبایل</label>
                    <input type="text" name="phone" class="form-control" value="<?php echo htmlspecialchars($user['phone']); ?>" required>
                </div>
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <a href="users.php" class="btn btn-secondary"><i class="fa fa-arrow-right me-1"></i> بازگشت</a>
                    <button type="submit" class="btn btn-success px-4"><i class="fa fa-save me-1"></i> ذخیره تغییرات</button>
                </div>
            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@next/dist/aos.js"></script>
    <script>AOS.init();</script>
</body>
</html>
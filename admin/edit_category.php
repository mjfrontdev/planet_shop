<?php
require_once '../config/database.php';
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die('شناسه دسته‌بندی نامعتبر است.');
}
$id = intval($_GET['id']);
$cat = $conn->query("SELECT * FROM categories WHERE id = $id")->fetch_assoc();
if (!$cat) die('دسته‌بندی یافت نشد.');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $desc = $_POST['description'];
    $conn->query("UPDATE categories SET name='$name', description='$desc' WHERE id=$id");
    header('Location: categories.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>ویرایش دسته‌بندی</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/gh/rastikerdar/vazirmatn@v33.003/Vazirmatn-font-face.css" rel="stylesheet" type="text/css" />
    <style>
        body { background: linear-gradient(135deg, #e8f5e9 0%, #c8e6c9 100%); min-height: 100vh; font-family: 'Vazirmatn', sans-serif; }
        .edit-card {
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 8px 32px rgba(60,60,60,0.12);
            padding: 2.5rem 2rem 2rem 2rem;
            max-width: 500px;
            margin: 2rem auto;
        }
        .edit-card .form-label { color: #388e3c; font-weight: 500; }
        .edit-card .form-control:focus {
            border-color: #43a047;
            box-shadow: 0 0 0 0.2rem rgba(67,160,71,.15);
        }
        .edit-title { color: #43a047; font-weight: bold; text-align: center; margin-bottom: 2rem; }
        .edit-btn { background: linear-gradient(90deg, #43a047 0%, #388e3c 100%); color: #fff; font-weight: bold; border-radius: 12px; }
        .edit-btn:hover { background: linear-gradient(90deg, #388e3c 0%, #43a047 100%); color: #fff; }
    </style>
</head>
<body>
    <div class="edit-card animate__animated animate__fadeInUp">
        <h2 class="edit-title mb-4"><i class="fa fa-edit me-2"></i>ویرایش دسته‌بندی</h2>
        <form method="post">
            <div class="mb-3">
                <label class="form-label">نام دسته‌بندی</label>
                <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($cat['name']) ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">توضیحات</label>
                <textarea name="description" class="form-control" required><?= htmlspecialchars($cat['description']) ?></textarea>
            </div>
            <div class="d-flex justify-content-between align-items-center mt-4">
                <button type="submit" class="btn edit-btn px-4"><i class="fa fa-save me-1"></i> ذخیره تغییرات</button>
                <a href="categories.php" class="btn btn-outline-success px-4"><i class="fa fa-arrow-right"></i> بازگشت</a>
            </div>
        </form>
    </div>
</body>
</html> 
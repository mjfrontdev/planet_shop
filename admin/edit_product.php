<?php
require_once '../config/database.php';
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die('شناسه محصول نامعتبر است.');
}
$id = intval($_GET['id']);
$product = $conn->query("SELECT * FROM products WHERE id = $id")->fetch_assoc();
if (!$product) die('محصول یافت نشد.');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $category = $_POST['category'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    $image_url = $product['image_url']; // پیش‌فرض عکس قبلی
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg','jpeg','png','gif','webp'];
        if (in_array($ext, $allowed)) {
            $new_name = 'product_' . uniqid() . '.' . $ext;
            $target = '../assets/images/' . $new_name;
            if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
                $image_url = 'assets/images/' . $new_name;
            }
        }
    }
    $stmt = $conn->prepare("UPDATE products SET name=?, category=?, price=?, description=?, image_url=? WHERE id=?");
    $stmt->bind_param("ssdssi", $name, $category, $price, $description, $image_url, $id);
    $stmt->execute();
    header('Location: products.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>ویرایش محصول</title>
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
        <h2 class="edit-title mb-4"><i class="fa fa-edit me-2"></i>ویرایش محصول</h2>
        <form method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label">نام محصول</label>
                <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($product['name']) ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">دسته‌بندی</label>
                <input type="text" name="category" class="form-control" value="<?= htmlspecialchars($product['category']) ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">قیمت</label>
                <input type="number" name="price" class="form-control" value="<?= htmlspecialchars($product['price']) ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">توضیحات</label>
                <textarea name="description" class="form-control" required><?= htmlspecialchars($product['description']) ?></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">عکس فعلی محصول</label><br>
                <img src="../<?= htmlspecialchars($product['image_url']) ?>" alt="عکس محصول" style="max-width:120px;max-height:120px;border-radius:10px;box-shadow:0 2px 8px #ccc;">
            </div>
            <div class="mb-3">
                <label class="form-label">عکس جدید (اختیاری)</label>
                <input type="file" name="image" class="form-control" accept="image/*">
                <small class="text-muted">در صورت انتخاب، عکس جدید جایگزین عکس فعلی می‌شود.</small>
            </div>
            <div class="d-flex justify-content-between align-items-center mt-4">
                <button type="submit" class="btn edit-btn px-4"><i class="fa fa-save me-1"></i> ذخیره تغییرات</button>
                <a href="products.php" class="btn btn-outline-success px-4"><i class="fa fa-arrow-right"></i> بازگشت</a>
            </div>
        </form>
    </div>
</body>
</html>
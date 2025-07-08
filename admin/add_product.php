<?php
require_once '../config/database.php';
// دریافت دسته‌بندی‌ها برای لیست کشویی
$categories = $conn->query("SELECT name FROM categories ORDER BY name ASC");
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $category = $_POST['category'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    $image_url = $_POST['image_url'];
    $stmt = $conn->prepare("INSERT INTO products (name, category, price, description, image_url) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssiss", $name, $category, $price, $description, $image_url);
    $stmt->execute();
    header('Location: products.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">

<head>
    <meta charset="UTF-8">
    <title>افزودن محصول جدید</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #e8f5e9 0%, #c8e6c9 100%);
            min-height: 100vh;
        }

        .add-product-card {
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 8px 32px rgba(60, 60, 60, 0.12);
            padding: 2.5rem 2rem 2rem 2rem;
            margin-top: 2rem;
            max-width: 500px;
            margin-left: auto;
            margin-right: auto;
            transition: box-shadow 0.2s;
        }

        .add-product-card:hover {
            box-shadow: 0 12px 40px rgba(60, 60, 60, 0.18);
        }

        .add-product-card .form-control:focus {
            border-color: #43a047;
            box-shadow: 0 0 0 0.2rem rgba(67, 160, 71, .15);
        }

        .add-btn {
            background: linear-gradient(90deg, #43a047 0%, #388e3c 100%);
            color: #fff;
            font-weight: bold;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(67, 160, 71, 0.08);
            transition: background 0.2s, box-shadow 0.2s;
        }

        .add-btn:hover,
        .add-btn:focus {
            background: linear-gradient(90deg, #388e3c 0%, #43a047 100%);
            color: #fff;
            box-shadow: 0 4px 16px rgba(67, 160, 71, 0.16);
        }

        .add-product-card .input-group-text {
            background: #e8f5e9;
            border: none;
            color: #43a047;
        }

        .add-product-card .form-label {
            color: #388e3c;
            font-weight: 500;
        }

        .add-product-card .form-title {
            color: #43a047;
            font-weight: bold;
        }

        .add-product-card .links a {
            color: #388e3c;
            text-decoration: underline;
        }

        .add-product-card .links a:hover {
            color: #43a047;
        }
    </style>
</head>

<body>
    <div class="container py-5">
        <div class="add-product-card animate__animated animate__fadeInUp" data-aos="zoom-in">
            <h2 class="form-title text-center mb-4"><i class="fa fa-plus text-success me-2"></i>افزودن محصول جدید</h2>
            <form method="post">
                <div class="mb-3">
                    <label class="form-label">نام محصول</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa fa-leaf"></i></span>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">دسته‌بندی</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa fa-list"></i></span>
                        <select name="category" class="form-control" required>
                            <option value="">انتخاب کنید...</option>
                            <?php if ($categories) foreach ($categories as $cat): ?>
                                <option value="<?= htmlspecialchars($cat['name']) ?>"><?= htmlspecialchars($cat['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">قیمت (تومان)</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa fa-money-bill-wave"></i></span>
                        <input type="number" name="price" class="form-control" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">توضیحات</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa fa-align-right"></i></span>
                        <textarea name="description" class="form-control" required></textarea>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">آدرس تصویر (URL)</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa fa-image"></i></span>
                        <input type="text" name="image_url" class="form-control" required>
                    </div>
                </div>
                <button type="submit" class="btn add-btn w-100 mb-2"><i class="fa fa-plus me-1"></i> افزودن محصول
                </button>
                <a href="products.php" class="btn btn-secondary w-100">بازگشت</a>
            </form>
        </div>
    </div>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
</body>

</html>
<?php
session_start();

// Check if user is already logged in
if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit();
}

// Handle registration form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once 'config/database.php';
    
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    $errors = [];
    
    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'ایمیل نامعتبر است';
    }
    
    // Check if email already exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    if ($stmt->get_result()->num_rows > 0) {
        $errors[] = 'این ایمیل قبلاً ثبت شده است';
    }
    
    // Check if phone already exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE phone = ?");
    $stmt->bind_param("s", $phone);
    $stmt->execute();
    if ($stmt->get_result()->num_rows > 0) {
        $errors[] = 'این شماره موبایل قبلاً ثبت شده است';
    }
    
    // Validate password
    if (strlen($password) < 6) {
        $errors[] = 'رمز عبور باید حداقل ۶ کاراکتر باشد';
    }
    
    if ($password !== $confirm_password) {
        $errors[] = 'رمز عبور و تکرار آن مطابقت ندارند';
    }
    
    if (empty($errors)) {
        // Hash password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        // Insert new user
        $stmt = $conn->prepare("INSERT INTO users (name, email, phone, password) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $email, $phone, $hashed_password);
        
        if ($stmt->execute()) {
            $_SESSION['success'] = 'ثبت‌نام با موفقیت انجام شد. لطفاً وارد شوید.';
            header('Location: login.php');
            exit();
        } else {
            $errors[] = 'خطا در ثبت‌نام. لطفاً دوباره تلاش کنید.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ثبت‌نام | فروشگاه گیاهان</title>
    
    <!-- Bootstrap RTL CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
    
    <!-- Vazirmatn Font -->
    <link href="https://cdn.jsdelivr.net/gh/rastikerdar/vazirmatn@v33.003/Vazirmatn-font-face.css" rel="stylesheet" type="text/css" />
    
    <!-- AOS Animation -->
    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
    <style>
        body { background: linear-gradient(135deg, #e8f5e9 0%, #c8e6c9 100%); min-height: 100vh; }
        .register-card {
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 8px 32px rgba(60,60,60,0.12);
            padding: 2.5rem 2rem 2rem 2rem;
            margin-top: 2rem;
            transition: box-shadow 0.2s;
        }
        .register-card:hover { box-shadow: 0 12px 40px rgba(60,60,60,0.18); }
        .register-card .form-control:focus {
            border-color: #43a047;
            box-shadow: 0 0 0 0.2rem rgba(67,160,71,.15);
        }
        .register-btn {
            background: linear-gradient(90deg, #43a047 0%, #388e3c 100%);
            color: #fff;
            font-weight: bold;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(67,160,71,0.08);
            transition: background 0.2s, box-shadow 0.2s;
        }
        .register-btn:hover, .register-btn:focus {
            background: linear-gradient(90deg, #388e3c 0%, #43a047 100%);
            color: #fff;
            box-shadow: 0 4px 16px rgba(67,160,71,0.16);
        }
        .register-card .input-group-text {
            background: #e8f5e9;
            border: none;
            color: #43a047;
        }
        .register-card .form-label { color: #388e3c; font-weight: 500; }
        .register-card .form-title { color: #43a047; font-weight: bold; }
        .register-card .links a { color: #388e3c; text-decoration: underline; }
        .register-card .links a:hover { color: #43a047; }
    </style>
</head>
<body>
<div id="global-loader"><div class="spinner"></div></div>
<div class="container">
    <div class="row justify-content-center align-items-center min-vh-100">
        <div class="col-md-6 col-lg-5">
            <div class="register-card animate__animated animate__fadeInUp" data-aos="zoom-in">
                <h2 class="form-title text-center mb-4"><i class="fa fa-user-plus text-success me-2"></i>ثبت‌نام</h2>
                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            <?php foreach ($errors as $error): ?>
                                <li><?php echo $error; ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
                <form method="POST" action="" id="register-form">
                    <div class="mb-3">
                        <label for="name" class="form-label">نام و نام خانوادگی</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fa fa-user"></i></span>
                            <input type="text" class="form-control" id="name" name="name" placeholder="نام و نام خانوادگی" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">ایمیل</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fa fa-envelope"></i></span>
                            <input type="email" class="form-control" id="email" name="email" placeholder="ایمیل" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="phone" class="form-label">شماره موبایل</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fa fa-phone"></i></span>
                            <input type="tel" class="form-control" id="phone" name="phone" placeholder="شماره موبایل" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">رمز عبور</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fa fa-lock"></i></span>
                            <input type="password" class="form-control" id="password" name="password" placeholder="رمز عبور" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="confirm_password" class="form-label">تکرار رمز عبور</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fa fa-lock"></i></span>
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="تکرار رمز عبور" required>
                        </div>
                    </div>
                    <button type="submit" class="btn register-btn w-100 mb-2" id="register-btn">
                        <i class="fa fa-user-plus me-1"></i> ثبت‌نام
                    </button>
                </form>
                <div class="links text-center mt-3">
                    <p>قبلاً ثبت‌نام کرده‌اید؟ <a href="login.php">وارد شوید</a></p>
                    <a href="index.php">بازگشت به صفحه اصلی</a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/aos@next/dist/aos.js"></script>
<script src="assets/js/main.js"></script>
<script>AOS.init();</script>
<script>
window.addEventListener('load', function() {
  document.getElementById('global-loader').style.display = 'none';
});
document.getElementById('register-form').addEventListener('submit', function(e) {
    var btn = document.getElementById('register-btn');
    btn.classList.add('btn-loading');
    btn.innerHTML = '<span>در حال ثبت‌نام...</span> <span class="spinner-border spinner-border-sm"></span>';
});
</script>
</body>
</html> 
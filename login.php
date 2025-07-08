<?php
session_start();

// Check if user is already logged in
if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit();
}
if (isset($_SESSION['admin_id'])) {
    header('Location: admin/dashboard.php');
    exit();
}

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once 'config/database.php';

    $email = $_POST['email'];
    $password = $_POST['password'];
    $error = 'ایمیل یا رمز عبور اشتباه است';

    // 1. Try users table
    $sql = "SELECT id, name, password FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            header('Location: dashboard.php');
            exit();
        }
    } else {
        // 2. Try admins table
        $sql = "SELECT id, email, password FROM admins WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows === 1) {
            $admin = $result->fetch_assoc();
            if (password_verify($password, $admin['password'])) {
                $_SESSION['admin_id'] = $admin['id'];
                $_SESSION['admin_email'] = $admin['email'];
                header('Location: admin/dashboard.php');
                exit();
            }
        }
    }
    // If neither user nor admin matched
    $error = 'ایمیل یا رمز عبور اشتباه است';
}
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ورود | فروشگاه گیاهان</title>

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
        .login-card {
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 8px 32px rgba(60,60,60,0.12);
            padding: 2.5rem 2rem 2rem 2rem;
            margin-top: 2rem;
            transition: box-shadow 0.2s;
        }
        .login-card:hover { box-shadow: 0 12px 40px rgba(60,60,60,0.18); }
        .login-card .form-control:focus {
            border-color: #43a047;
            box-shadow: 0 0 0 0.2rem rgba(67,160,71,.15);
        }
        .login-btn {
            background: linear-gradient(90deg, #43a047 0%, #388e3c 100%);
            color: #fff;
            font-weight: bold;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(67,160,71,0.08);
            transition: background 0.2s, box-shadow 0.2s;
        }
        .login-btn:hover, .login-btn:focus {
            background: linear-gradient(90deg, #388e3c 0%, #43a047 100%);
            color: #fff;
            box-shadow: 0 4px 16px rgba(67,160,71,0.16);
        }
        .login-card .input-group-text {
            background: #e8f5e9;
            border: none;
            color: #43a047;
        }
        .login-card .form-label { color: #388e3c; font-weight: 500; }
        .login-card .form-title { color: #43a047; font-weight: bold; }
        .login-card .links a { color: #388e3c; text-decoration: underline; }
        .login-card .links a:hover { color: #43a047; }
    </style>
</head>

<body>
    <div id="global-loader">
        <div class="spinner"></div>
    </div>
    <div class="container">
        <div class="row justify-content-center align-items-center min-vh-100">
            <div class="col-md-6 col-lg-4">
                <div class="login-card animate__animated animate__fadeInUp" data-aos="zoom-in">
                    <h2 class="form-title text-center mb-4"><i class="fa fa-sign-in-alt text-success me-2"></i>ورود به حساب</h2>
                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger"><?php echo $error; ?></div>
                    <?php endif; ?>
                    <form method="POST" action="" id="login-form">
                        <div class="mb-3">
                            <label for="email" class="form-label">ایمیل</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa fa-envelope"></i></span>
                                <input type="email" class="form-control" id="email" name="email" placeholder="ایمیل" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">رمز عبور</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa fa-lock"></i></span>
                                <input type="password" class="form-control" id="password" name="password" placeholder="رمز عبور" required>
                            </div>
                        </div>
                        <button type="submit" class="btn login-btn w-100 mb-2" id="login-btn">
                            <i class="fa fa-sign-in-alt me-1"></i> ورود
                        </button>
                    </form>
                    <div class="links text-center mt-3">
                        <p>حساب کاربری ندارید؟ <a href="register.php">ثبت‌نام کنید</a></p>
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
    <script>
        AOS.init();
    </script>
    <script>
        window.addEventListener('load', function() {
            document.getElementById('global-loader').style.display = 'none';
        });
        document.getElementById('login-form').addEventListener('submit', function(e) {
            var btn = document.getElementById('login-btn');
            btn.classList.add('btn-loading');
            btn.innerHTML = '<span>در حال ورود...</span> <span class="spinner-border spinner-border-sm"></span>';
        });
    </script>
</body>

</html>
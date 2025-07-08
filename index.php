<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>فروشگاه گیاهان | صفحه اصلی</title>
    
    <!-- Bootstrap RTL CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- AOS CSS -->
    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
    
    <!-- Vazirmatn Font -->
    <link href="https://cdn.jsdelivr.net/gh/rastikerdar/vazirmatn@v33.003/Vazirmatn-font-face.css" rel="stylesheet" type="text/css" />
</head>
<body>
<?php include 'partials/navbar.php'; ?>
    <header class="main-header position-relative">
        <div class="header-bg d-flex align-items-center justify-content-center">
            <div class="text-center text-white">
                <h1 class="display-4 fw-bold" data-aos="fade-down">به فروشگاه گیاهان سبز خوش آمدید</h1>
                <p class="lead" data-aos="fade-up">مجموعه‌ای از بهترین گیاهان آپارتمانی و دارویی</p>
                <a href="products.php" class="btn btn-success btn-lg" data-aos="zoom-in">مشاهده محصولات</a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main>
        <!-- مطالب ویژه -->
        <section class="py-5 bg-light" data-aos="fade-up">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-6 mb-4 mb-md-0">
                        <img src="https://images.unsplash.com/photo-1465101046530-73398c7f28ca?auto=format&fit=crop&w=600&q=80" class="img-fluid rounded shadow" alt="نکات نگهداری گیاهان">
                    </div>
                    <div class="col-md-6">
                        <h3 class="mb-3">نکات طلایی نگهداری گیاهان آپارتمانی</h3>
                        <ul>
                            <li>نور مناسب و غیرمستقیم برای بیشتر گیاهان ضروری است.</li>
                            <li>آبیاری منظم اما بدون غرقاب کردن خاک انجام دهید.</li>
                            <li>برگ‌های گیاه را هر چند وقت یکبار با دستمال مرطوب تمیز کنید.</li>
                            <li>کوددهی ماهانه به رشد بهتر گیاه کمک می‌کند.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>
        <section class="py-5" data-aos="fade-up">
            <div class="container">
                <div class="row text-center">
                    <div class="col-md-4 mb-4">
                        <div class="card h-100 shadow border-0">
                            <div class="card-body">
                                <i class="fas fa-truck fa-2x mb-3 text-success"></i>
                                <h5 class="card-title">ارسال سریع و مطمئن</h5>
                                <p class="card-text">تمام سفارش‌ها در کوتاه‌ترین زمان ممکن و با بسته‌بندی ایمن ارسال می‌شوند.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-4">
                        <div class="card h-100 shadow border-0">
                            <div class="card-body">
                                <i class="fas fa-leaf fa-2x mb-3 text-success"></i>
                                <h5 class="card-title">تضمین سلامت گیاه</h5>
                                <p class="card-text">تمامی گیاهان قبل از ارسال بررسی و سلامت آن‌ها تضمین می‌شود.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-4">
                        <div class="card h-100 shadow border-0">
                            <div class="card-body">
                                <i class="fas fa-headset fa-2x mb-3 text-success"></i>
                                <h5 class="card-title">پشتیبانی حرفه‌ای</h5>
                                <p class="card-text">تیم پشتیبانی ما همیشه آماده پاسخگویی به سوالات و مشکلات شماست.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="py-5 bg-light" data-aos="fade-up">
            <div class="container">
                <h3 class="mb-4 text-center">مقالات و مطالب خواندنی</h3>
                <div class="row">
                    <div class="col-md-6 mb-4">
                        <div class="card h-100 shadow border-0">
                            <div class="card-body">
                                <h5 class="card-title">۵ گیاه مقاوم برای آپارتمان‌های کم‌نور</h5>
                                <p class="card-text">اگر نور منزل شما کم است، گیاهانی مثل زامیفولیا، سانسوریا و پتوس انتخاب‌های عالی هستند که نیاز نوری کمی دارند و به راحتی رشد می‌کنند.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-4">
                        <div class="card h-100 shadow border-0">
                            <div class="card-body">
                                <h5 class="card-title">چرا گیاهان را در خانه نگهداری کنیم؟</h5>
                                <p class="card-text">گیاهان علاوه بر زیبایی، باعث تصفیه هوا، کاهش استرس و افزایش انرژی مثبت در محیط زندگی می‌شوند.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Categories Section -->
        <section id="categories" class="py-5" data-aos="fade-up">
            <div class="container">
                <h2 class="text-center mb-4">دسته‌بندی‌های محصولات</h2>
                <div class="row">
                    <div class="col-md-4 mb-4">
                        <div class="card">
                            <img src="assets/images/kendal-TW2bfT_tWDI-unsplash.jpg" class="card-img-top" alt="گیاهان آپارتمانی">
                            <div class="card-body">
                                <h5 class="card-title">گیاهان آپارتمانی</h5>
                                <p class="card-text">مجموعه‌ای از زیباترین گیاهان مناسب برای فضای داخلی</p>
                                <a href="products.php?cat=apartment" class="btn btn-success">مشاهده محصولات</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-4">
                        <div class="card">
                            <img src="assets/images/ceyda-ciftci-dDVU6D_6T80-unsplash.jpg" class="card-img-top" alt="گیاهان دارویی">
                            <div class="card-body">
                                <h5 class="card-title">گیاهان دارویی</h5>
                                <p class="card-text">گیاهان دارویی با خواص درمانی و سلامتی</p>
                                <a href="products.php?cat=medical" class="btn btn-success">مشاهده محصولات</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-4">
                        <div class="card">
                            <img src="assets/images/jackie-dilorenzo-RyLsRzy9jIA-unsplash.jpg" class="card-img-top" alt="گیاهان زینتی">
                            <div class="card-body">
                                <h5 class="card-title">گیاهان زینتی</h5>
                                <p class="card-text">گیاهان تزئینی برای زیبایی بیشتر فضای شما</p>
                                <a href="products.php?cat=ornamental" class="btn btn-success">مشاهده محصولات</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Products Section -->
        <section id="products" class="py-5 bg-light" data-aos="fade-up">
            <div class="container">
                <h2 class="text-center mb-4">محصولات برتر</h2>
                <div class="row" id="products-container">
                    <!-- Products will be loaded dynamically -->
                </div>
            </div>
        </section>

        <!-- Contact Section -->
        <section id="contact" class="py-5" data-aos="fade-up">
            <div class="container">
                <h2 class="text-center mb-4">تماس با ما</h2>
                <div class="row">
                    <div class="col-md-6">
                        <form id="contact-form">
                            <div class="mb-3">
                                <label for="name" class="form-label">نام</label>
                                <input type="text" class="form-control" id="name" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">ایمیل</label>
                                <input type="email" class="form-control" id="email" required>
                            </div>
                            <div class="mb-3">
                                <label for="message" class="form-label">پیام</label>
                                <textarea class="form-control" id="message" rows="5" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-success">ارسال پیام</button>
                        </form>
                    </div>
                    <div class="col-md-6">
                        <div class="contact-info">
                            <h4>اطلاعات تماس</h4>
                            <p><i class="fas fa-map-marker-alt"></i> آدرس: تهران، خیابان ولیعصر</p>
                            <p><i class="fas fa-phone"></i> تلفن: ۰۲۱-۱۲۳۴۵۶۷۸</p>
                            <p><i class="fas fa-envelope"></i> ایمیل: info@plant-shop.ir</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
<?php include 'partials/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@next/dist/aos.js"></script>
    <script src="assets/js/main.js"></script>
    <script>AOS.init();</script>
</body>
</html> 
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تماس با ما | فروشگاه گیاهان سبز</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://cdn.jsdelivr.net/gh/rastikerdar/vazirmatn@v33.003/Vazirmatn-font-face.css" rel="stylesheet" type="text/css" />
</head>
<body>
<?php include 'partials/navbar.php'; ?>
<section class="py-5 bg-light">
    <div class="container">
        <h2 class="text-center mb-5" data-aos="fade-down">تماس با ما</h2>
        <div class="row">
            <div class="col-md-6 mb-4" data-aos="fade-left">
                <form id="contact-form" class="p-4 rounded shadow bg-white animate__animated animate__fadeInUp">
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
                    <button type="submit" class="btn btn-success w-100">ارسال پیام</button>
                </form>
            </div>
            <div class="col-md-6 mb-4" data-aos="fade-right">
                <div class="contact-info p-4 rounded shadow bg-white animate__animated animate__fadeInUp">
                    <h4>اطلاعات تماس</h4>
                    <p><i class="fas fa-map-marker-alt"></i> آدرس: تهران، خیابان ولیعصر</p>
                    <p><i class="fas fa-phone"></i> تلفن: ۰۲۱-۱۲۳۴۵۶۷۸</p>
                    <p><i class="fas fa-envelope"></i> ایمیل: info@plant-shop.ir</p>
                </div>
            </div>
        </div>
    </div>
</section>
<?php include 'partials/footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/aos@next/dist/aos.js"></script>
<script src="assets/js/main.js"></script>
<script>AOS.init();</script>
</body>
</html> 
<?php
require_once 'config/database.php';
$category = isset($_GET['cat']) ? $_GET['cat'] : '';
$cat_map = [
    'apartment' => 'گیاهان آپارتمانی',
    'medical' => 'گیاهان دارویی',
    'ornamental' => 'گیاهان زینتی',
    'cactus' => 'کاکتوس‌ها',
    'flowering' => 'گیاهان گلدار',
    'hardy' => 'گیاهان مقاوم',
    'hanging' => 'گیاهان آویزی',
    'airplant' => 'گیاهان هوازی'
];
$where = '';
if ($category && isset($cat_map[$category])) {
    $where = "WHERE category = '" . $cat_map[$category] . "'";
}
$products = $conn->query("SELECT * FROM products $where ORDER BY id DESC LIMIT 20");
$sample_products = [
    [
        'name' => 'فیکوس الاستیکا',
        'description' => 'گیاه مقاوم و زیبا مناسب فضای داخلی.',
        'price' => 350000,
        'category' => 'آپارتمانی',
        'image_url' => 'assets/images/kendal-TW2bfT_tWDI-unsplash.jpg',
        'rating' => 4.5
    ],
    [
        'name' => 'سانسوریا',
        'description' => 'گیاه تصفیه‌کننده هوا و کم‌نیاز به نور.',
        'price' => 220000,
        'category' => 'آپارتمانی',
        'image_url' => 'assets/images/alex-perri-bmM_IdLd1SA-unsplash.jpg',
        'rating' => 4.8
    ],
    [
        'name' => 'کاکتوس اریوکارپوس',
        'description' => 'کاکتوس کمیاب و کلکسیونی.',
        'price' => 480000,
        'category' => 'زینتی',
        'image_url' => 'assets/images/jackie-dilorenzo-RyLsRzy9jIA-unsplash.jpg',
        'rating' => 4.2
    ],
    [
        'name' => 'پتوس طلایی',
        'description' => 'گیاه رونده و مقاوم با برگ‌های طلایی.',
        'price' => 180000,
        'category' => 'آپارتمانی',
        'image_url' => 'assets/images/ren-ran-bBiuSdck8tU-unsplash.jpg',
        'rating' => 4.7
    ],
    [
        'name' => 'آلوئه ورا',
        'description' => 'گیاه دارویی با خواص درمانی فراوان.',
        'price' => 250000,
        'category' => 'دارویی',
        'image_url' => 'assets/images/ceyda-ciftci-dDVU6D_6T80-unsplash.jpg',
        'rating' => 4.9
    ],
    [
        'name' => 'زامیفولیا',
        'description' => 'گیاه مقاوم به کم‌آبی و نور کم.',
        'price' => 320000,
        'category' => 'آپارتمانی',
        'image_url' => 'assets/images/pexels-kate-amos-1408770-2718447.jpg',
        'rating' => 4.6
    ],
    [
        'name' => 'پپرومیا',
        'description' => 'گیاه کوچک و مناسب میز کار.',
        'price' => 150000,
        'category' => 'زینتی',
        'image_url' => 'assets/images/pexels-iriser-1005715.jpg',
        'rating' => 4.3
    ],
    [
        'name' => 'فیلودندرون',
        'description' => 'گیاه برگ‌درشت و بسیار زیبا.',
        'price' => 410000,
        'category' => 'آپارتمانی',
        'image_url' => 'assets/images/pexels-daniel-1055408.jpg',
        'rating' => 4.4
    ],
    [
        'name' => 'کالاتیا',
        'description' => 'گیاه برگ‌دار با نقش و نگار خاص.',
        'price' => 370000,
        'category' => 'زینتی',
        'image_url' => 'assets/images/pexels-valeriya-827518.jpg',
        'rating' => 4.1
    ],
];
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>محصولات | فروشگاه گیاهان سبز</title>
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
        <h2 class="text-center mb-5" data-aos="fade-down">محصولات <?php echo $category && isset($cat_map[$category]) ? $cat_map[$category] : 'فروشگاه'; ?></h2>
        <div class="row">
            <?php if($products && $products->num_rows > 0): while($product = $products->fetch_assoc()): ?>
            <div class="col-md-4 mb-4" data-aos="fade-up">
                <div class="card product-card h-100 shadow animate__animated animate__fadeInUp">
                    <img src="<?php echo htmlspecialchars($product['image_url']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($product['name']); ?>">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($product['name']); ?></h5>
                        <p class="card-text"><?php echo htmlspecialchars($product['description']); ?></p>
                        <div class="mb-2">
                            <?php $rating = isset($product['rating']) ? round($product['rating']) : 4; for($r=1;$r<=5;$r++): ?>
                                <i class="fa fa-star<?php echo $r <= $rating ? ' text-warning' : '-o text-muted'; ?>"></i>
                            <?php endfor; ?>
                            <span class="ms-2 small text-muted"><?php echo isset($product['rating']) ? number_format($product['rating'],1) : '4.5'; ?></span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="price"><?php echo number_format($product['price']); ?> تومان</span>
                            <button class="btn btn-success add-to-cart"
                                    data-id="<?php echo $product['id']; ?>"
                                    data-name="<?php echo htmlspecialchars($product['name']); ?>"
                                    data-price="<?php echo $product['price']; ?>"
                                    data-image="<?php echo htmlspecialchars($product['image_url']); ?>">
                                <i class="fas fa-shopping-cart"></i> افزودن به سبد
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <?php endwhile; else: foreach($sample_products as $i => $product): ?>
            <div class="col-md-4 mb-4" data-aos="fade-up">
                <div class="card product-card h-100 shadow animate__animated animate__fadeInUp">
                    <img src="<?php echo htmlspecialchars($product['image_url']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($product['name']); ?>">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($product['name']); ?></h5>
                        <p class="card-text"><?php echo htmlspecialchars($product['description']); ?></p>
                        <div class="mb-2">
                            <?php $rating = isset($product['rating']) ? round($product['rating']) : 4; for($r=1;$r<=5;$r++): ?>
                                <i class="fa fa-star<?php echo $r <= $rating ? ' text-warning' : '-o text-muted'; ?>"></i>
                            <?php endfor; ?>
                            <span class="ms-2 small text-muted"><?php echo isset($product['rating']) ? number_format($product['rating'],1) : '4.5'; ?></span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="price"><?php echo number_format($product['price']); ?> تومان</span>
                            <button class="btn btn-success add-to-cart"
                                    data-id="<?php echo $i+1; ?>"
                                    data-name="<?php echo htmlspecialchars($product['name']); ?>"
                                    data-price="<?php echo $product['price']; ?>"
                                    data-image="<?php echo htmlspecialchars($product['image_url']); ?>">
                                <i class="fas fa-shopping-cart"></i> افزودن به سبد
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; endif; ?>
        </div>
    </div>
</section>
<?php include 'partials/footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/aos@next/dist/aos.js"></script>
<script src="assets/js/main.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.12/dist/sweetalert2.all.min.js"></script>
<script>AOS.init();</script>
</body>
</html> 
<?php
session_start();
header('Content-Type: application/json');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id'] ?? 0);
    $name = trim($_POST['name'] ?? '');
    $price = floatval($_POST['price'] ?? 0);
    $image = trim($_POST['image'] ?? '');
    if ($id && $name && $price) {
        if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];
        if (isset($_SESSION['cart'][$id])) {
            $_SESSION['cart'][$id]['qty'] += 1;
        } else {
            $_SESSION['cart'][$id] = [
                'id' => $id,
                'name' => $name,
                'price' => $price,
                'image' => $image,
                'qty' => 1
            ];
        }
        $count = 0;
        foreach ($_SESSION['cart'] as $item) $count += $item['qty'];
        echo json_encode(['success' => true, 'count' => $count]);
        exit;
    }
}
echo json_encode(['success' => false]); 
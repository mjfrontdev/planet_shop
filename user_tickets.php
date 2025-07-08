<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}
require_once 'config/database.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');
    if ($subject && $message) {
        $stmt = $conn->prepare("INSERT INTO tickets (user_id, subject, message, status, created_at) VALUES (?, ?, ?, 'open', NOW())");
        $stmt->bind_param('iss', $user_id, $subject, $message);
        $stmt->execute();
        $stmt->close();
        $_SESSION['ticket_success'] = 'تیکت با موفقیت ثبت شد!';
    } else {
        $_SESSION['ticket_error'] = 'لطفا همه فیلدها را پر کنید.';
    }
}
header('Location: dashboard.php#tickets');
exit(); 
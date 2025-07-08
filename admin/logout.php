<?php
session_start();
// Destroy only admin session variables
unset($_SESSION['admin_id']);
unset($_SESSION['admin_email']);
session_write_close();
header('Location: ../index.php');
exit(); 
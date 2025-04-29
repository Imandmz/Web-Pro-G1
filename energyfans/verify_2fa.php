<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id']) && isset($_SESSION['temp_user_id'])) {
    header('Location: login.php');
    exit();
}

// Hier normalerweise echte 2FA-Prüfung (Google Authenticator)
$_SESSION['user_id'] = $_SESSION['user_id'] ?? $_SESSION['temp_user_id'];
unset($_SESSION['temp_user_id']);
header('Location: index.php');
exit();
?>

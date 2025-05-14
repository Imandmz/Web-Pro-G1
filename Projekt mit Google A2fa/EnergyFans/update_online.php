<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    exit;
}

$stmt = $pdo->prepare("
    INSERT INTO online (user_id, timestamp)
    VALUES (?, NOW())
    ON DUPLICATE KEY UPDATE timestamp = NOW()
");
$stmt->execute([$_SESSION['user_id']]);

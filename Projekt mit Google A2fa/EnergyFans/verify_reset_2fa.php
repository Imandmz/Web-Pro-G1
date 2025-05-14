<?php
session_start();
require_once 'config.php';
require_once __DIR__ . '/includes/GoogleAuthenticator.php';

if (!isset($_SESSION['reset_user_id'], $_SESSION['reset_secret'])) {
    header('Location: forget_password.php');
    exit();
}

$ga = new PHPGangsta_GoogleAuthenticator();
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $code = $_POST['code'] ?? '';
    $secret = $_SESSION['reset_secret'];

    if ($ga->verifyCode($secret, $code, 2)) {
        $_SESSION['reset_verified'] = true;
        header('Location: reset_password.php');
        exit();
    } else {
        $error = "❌ Falscher Code. Versuche es erneut.";
    }
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8">
  <title>2FA Verifizierung</title>
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<div class="login-form">
  <h2>2FA Bestätigung</h2>
  <?php if ($error): ?>
    <p class="error"><?= htmlspecialchars($error) ?></p>
  <?php endif; ?>
  <form method="post">
    <input type="text" name="code" placeholder="6-stelliger Code" required>
    <button type="submit">Verifizieren</button>
  </form>
</div>
</body>
</html>

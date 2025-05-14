<?php
session_start();
require 'config.php';

if (!isset($_SESSION['reset_user_id'], $_SESSION['reset_verified'])) {
    header('Location: login.php');
    exit();
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pw1 = $_POST['password1'] ?? '';
    $pw2 = $_POST['password2'] ?? '';

    if ($pw1 !== $pw2) {
        $error = "Die Passwörter stimmen nicht überein.";
    } elseif (strlen($pw1) < 8) {
        $error = "Passwort muss mindestens 8 Zeichen lang sein.";
    } else {
        $hash = hash('sha512', $pw1);
        $stmt = $pdo->prepare("UPDATE kunden SET password = ?, reset_token = NULL, reset_token_time = NULL WHERE id = ?");
        $stmt->execute([$hash, $_SESSION['reset_user_id']]);

        unset($_SESSION['reset_user_id'], $_SESSION['reset_verified'], $_SESSION['reset_secret']);
        $success = "Passwort erfolgreich zurückgesetzt! <a href='login.php'>Zum Login</a>";
    }
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8">
  <title>Neues Passwort</title>
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<div class="login-form">
  <h2>🔑 Neues Passwort setzen</h2>
  <?php if ($error): ?><p class="error"><?= htmlspecialchars($error) ?></p><?php endif; ?>
  <?php if ($success): ?><p class="success"><?= $success ?></p><?php else: ?>
  <form method="post">
    <input type="password" name="password1" placeholder="Neues Passwort" required>
    <input type="password" name="password2" placeholder="Bestätigen" required>
    <button type="submit">Speichern</button>
  </form>
  <?php endif; ?>
</div>
</body>
</html>

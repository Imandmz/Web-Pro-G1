<?php
require 'config.php';
session_start();

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'])) {
    $email = $_POST['email'];

    $stmt = $pdo->prepare("SELECT id, secret FROM kunden WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user) {
        $_SESSION['reset_user_id'] = $user['id'];
        $_SESSION['reset_secret'] = $user['secret'];
        header('Location: verify_reset_2fa.php');
        exit();
    } else {
        $error = "Falls die E-Mail registriert ist, kannst du im nächsten Schritt fortfahren.";
    }
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8">
  <title>Passwort vergessen</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
        background: url('assets/images/background.jpg') no-repeat center center fixed;
        background-size: cover;
        color: white;
    }
    .form-box {
        background: rgba(0, 0, 0, 0.75);
        padding: 30px;
        max-width: 400px;
        margin: 100px auto;
        border-radius: 12px;
    }
    input {
        margin-bottom: 15px;
        background: rgba(255, 255, 255, 0.1);
        border: none;
        color: white;
    }
  </style>
</head>
<body>

<div class="form-box">
  <h2 class="text-center">🔐 Passwort vergessen</h2>
  <?php if ($error): ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>
  <?php if ($success): ?><div class="alert alert-success"><?= htmlspecialchars($success) ?></div><?php endif; ?>

  <form method="post">
    <input type="email" name="email" class="form-control" placeholder="Deine E-Mail" required>
    <button class="btn btn-success w-100">Weiter mit 2FA</button>
  </form>
</div>

</body>
</html>


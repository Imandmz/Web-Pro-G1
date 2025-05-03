<?php
session_start();
require_once 'config.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'], $_POST['password'])) {
    $email = $_POST['email'];
    $password = hash('sha512', $_POST['password']); // SHA512 Hash

    $stmt = $pdo->prepare("SELECT id, password, secret, first_login FROM kunden WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && $user['password'] === $password) {
        if ($user['first_login']) {
            $_SESSION['temp_user_id'] = $user['id'];
            header('Location: password_change.php');
            exit();
        } else {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_email'] = $email;
            $_SESSION['2fa_secret'] = $user['secret'];
            header('Location: verify_2fa.php');
            exit();
        }
    } else {
        $error = "Falsche E-Mail oder Passwort.";
    }
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Login - EnergyFans</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: url('assets/images/background.jpg') no-repeat center center fixed;
            background-size: cover;
            font-family: Arial, sans-serif;
            color: #fff;
        }
        .login-form {
            max-width: 400px;
            margin: 120px auto;
            padding: 2rem;
            background-color: rgba(0, 0, 0, 0.75);
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0,0,0,0.6);
        }
        .login-form input,
        .login-form button {
            width: 100%;
            margin-bottom: 15px;
            padding: 12px;
            border: none;
            border-radius: 5px;
        }
        .login-form input {
            background-color: rgba(255,255,255,0.1);
            color: #fff;
        }
        .login-form input::placeholder {
            color: #ccc;
        }
        .login-form button {
            background-color: #28a745; /* grün */
            color: white;
            font-weight: bold;
        }
        .login-form a {
            color: #f8d7da;
            text-decoration: underline;
        }
        .login-form .error {
            color: #f8d7da;
            background: rgba(255,0,0,0.2);
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="login-form">
        <h2 class="text-center mb-4">Login</h2>

        <?php if (!empty($error)): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="post" action="">
            <input type="email" name="email" placeholder="E-Mail" required>
            <input type="password" name="password" placeholder="Passwort" required>
            <button class="btn btn-success mt-2">Login</button>
        </form>

        <div class="d-flex justify-content-between">
            <a href="register.php">Noch kein Konto?</a>
            <a href="forget_password.php">Passwort vergessen?</a>
        </div>
    </div>

    <?php include 'footer.php'; ?>
</body>
</html>

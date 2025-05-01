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
            // Benutzer muss Passwort ändern
            $_SESSION['temp_user_id'] = $user['id'];
            header('Location: password_change.php');
            exit();
        } else {
            // Normal einloggen
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
    <script src="assets/js/scripts.js"></script>
</head>
<body>
<div class="login-form">
    <h2>Login</h2>
    <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>
    <form name="loginForm" method="post" action="" onsubmit="return validateForm();">
        <input type="email" name="email" placeholder="E-Mail" required><br>
        <input type="password" name="password" placeholder="Passwort" required><br>
        <button type="submit">Login</button>
    </form>
    <p>Noch kein Konto? <a href="register.php">Registrieren</a></p>
</div>
<?php include 'footer.php'; ?>
</body>
</html>

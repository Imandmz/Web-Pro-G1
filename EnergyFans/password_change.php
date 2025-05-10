<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['temp_user_id'])) {
    header('Location: login.php');
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['password1'], $_POST['password2'])) {
    if ($_POST['password1'] !== $_POST['password2']) {
        $error = "Passwörter stimmen nicht überein.";
    } else {
        $password = $_POST['password1'];

        // Passwort-Regeln prüfen
        if (strlen($password) < 9 || !preg_match('/[A-Z]/', $password) || !preg_match('/[a-z]/', $password) || !preg_match('/[0-9]/', $password)) {
            $error = "Passwort muss mindestens 9 Zeichen, Großbuchstaben, Kleinbuchstaben und Zahl enthalten.";
        } else {
            $newPasswordHash = hash('sha512', $password);

            $stmt = $pdo->prepare("UPDATE kunden SET password = ?, first_login = 0 WHERE id = ?");
            $stmt->execute([$newPasswordHash, $_SESSION['temp_user_id']]);

            unset($_SESSION['temp_user_id']);
            header('Location: login.php');
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Passwort ändern</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<div class="login-form">
    <h2>Passwort ändern</h2>
    <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>
    <form method="post" action="">
        <input type="password" name="password1" placeholder="Neues Passwort" required><br>
        <input type="password" name="password2" placeholder="Passwort bestätigen" required><br>
        <button type="submit">Passwort speichern</button>
    </form>
</div>
<?php include 'footer.php'; ?>
</body>
</html>

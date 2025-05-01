<?php
session_start();
require_once 'config.php';

$error = '';

if (isset($_POST['email'])) {
    $email = $_POST['email'];

    if (strlen($email) < 5 || strpos($email, '@') === false) {
        $error = "Ungültige E-Mail Adresse.";
    } else {
        $stmt = $pdo->prepare("SELECT id FROM kunden WHERE email = ?");
        $stmt->execute([$email]);

        if ($stmt->rowCount() === 0) {
            $password = bin2hex(random_bytes(5)); // 10 Zeichen zufälliges Passwort
            $passwordHash = hash('sha512', $password);
            $secret = bin2hex(random_bytes(10));

            $insert = $pdo->prepare("INSERT INTO kunden (email, password, secret, punkte, first_login) VALUES (?, ?, ?, 100, 1)");
            $insert->execute([$email, $passwordHash, $secret]);

            // SCHRITTWICHTIG: Passwort AUSGEBEN, weil lokal Mail vielleicht nicht geht
            echo "<p>Ihr Startpasswort ist: <strong>$password</strong></p>";
            echo "<p>Bitte speichern Sie dieses Passwort ab!</p>";

            // Oder Mail verschicken (wenn Mail-Server da ist)
            // mail($email, "Ihr Startpasswort für EnergyFans", "Ihr Passwort lautet: $password");

            exit();
        } else {
            $error = "Benutzer existiert bereits.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Registrieren - EnergyFans</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<div class="login-form">
    <h2>Registrieren</h2>
    <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>
    <form method="post" action="">
        <input type="email" name="email" placeholder="E-Mail" required><br>
        <button type="submit">Registrieren</button>
    </form>
</div>
<?php include 'footer.php'; ?>
</body>
</html>

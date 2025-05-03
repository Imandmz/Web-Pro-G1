<?php
require 'config.php';
session_start();

$infoMessage = '';

if (isset($_POST['email'])) {
    $email = $_POST['email'];
    $token = bin2hex(random_bytes(32));

    $stmt = $pdo->prepare("SELECT id FROM kunden WHERE email = ?");
    $stmt->execute([$email]);

    if ($stmt->rowCount() > 0) {
        $stmt = $pdo->prepare("UPDATE kunden SET reset_token=?, reset_token_time=NOW() WHERE email=?");
        $stmt->execute([$token, $email]);

        $link = "http://localhost/energyfans/reset_password.php?token=$token";
        mail($email, "Passwort zurücksetzen", "Klicke auf den Link, um dein Passwort zurückzusetzen: $link");

        $infoMessage = "Wenn die E-Mail existiert, wurde ein Link zum Zurücksetzen gesendet.";
    } else {
        $infoMessage = "Wenn die E-Mail existiert, wurde ein Link zum Zurücksetzen gesendet."; // Gleiche Meldung, kein Leak
    }
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Passwort vergessen - EnergyFans</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        body {
            background: url('assets/images/background.jpg') no-repeat center center fixed;
            background-size: cover;
        }
        .container-box {
            background-color: rgba(255, 255, 255, 0.92);
            padding: 2rem;
            border-radius: 15px;
            margin-top: 80px;
        }
    </style>
</head>
<body>
<?php include 'navbar.php'; ?>

<div class="container">
    <div class="container-box mx-auto" style="max-width: 500px;">
        <h2 class="mb-4">Passwort vergessen</h2>
        <?php if ($infoMessage): ?>
            <div class="alert alert-info"><?= htmlspecialchars($infoMessage) ?></div>
        <?php endif; ?>
        <form method="post">
            <div class="mb-3">
                <label for="email" class="form-label">E-Mail-Adresse</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="Deine E-Mail" required>
            </div>
            <button type="submit" class="btn btn-danger w-100">Passwort zurücksetzen</button>
        </form>
    </div>
</div>

<?php include 'footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

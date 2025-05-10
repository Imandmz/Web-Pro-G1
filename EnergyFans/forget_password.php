<?php
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'])) {
    $email = $_POST['email'];
    $token = bin2hex(random_bytes(32));

    // In DB speichern
    $stmt = $pdo->prepare("UPDATE kunden SET reset_token = ?, reset_token_time = NOW() WHERE email = ?");
    $stmt->execute([$token, $email]);

    // E-Mail mit Link (nur wenn Adresse in DB ist)
    if ($stmt->rowCount()) {
        $link = "http://localhost/energyfans/reset_password.php?token=$token";
        mail($email, "Passwort zurücksetzen", "Hallo,\n\nklicke hier um dein Passwort zurückzusetzen:\n$link\n\nDieser Link ist 30 Minuten gültig.");
    }

    $message = "Wenn die E-Mail registriert ist, wurde eine Nachricht zum Zurücksetzen gesendet.";
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Passwort vergessen</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: url('assets/images/background.jpg') no-repeat center center fixed;
            background-size: cover;
            color: #fff;
        }
        .form-box {
            max-width: 400px;
            margin: 100px auto;
            padding: 2rem;
            background: rgba(0,0,0,0.75);
            border-radius: 15px;
        }
        input {
            margin-bottom: 1rem;
            background: rgba(255,255,255,0.1);
            color: #fff;
        }
        input::placeholder {
            color: #ccc;
        }
        button {
            width: 100%;
            background-color: #28a745;
            color: #fff;
            font-weight: bold;
            border: none;
        }
        .btn-back {
            width: 100%;
            background-color: #007bff;
            color: #fff;
            font-weight: bold;
            border: none;
            margin-top: 1rem;
        }
    </style>
</head>
<body>
    <div class="form-box">
        <h3 class="text-center">Passwort vergessen</h3>
        <?php if (!empty($message)) echo "<p style='color:lightgreen;'>$message</p>"; ?>
        <form method="post">
            <input type="email" class="form-control" name="email" placeholder="Deine E-Mail" required>
            <button class="btn btn-success mt-2">Link zum Zurücksetzen senden</button>
        </form>
        <!-- Button zum Zurückkehren zum Login -->
        <form action="login.php" method="get">
            <button class="btn btn-success mt-2">Zurück zum Login</button>
        </form>
    </div>
</body>
</html>

<?php
session_start();
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Vielen Dank - EnergyFans</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include 'navbar.php'; ?>

<div class="container container-box mt-5 text-center">
    <h1>Vielen Dank für Ihre Bestellung!</h1>
    <p>Wir haben Ihre Bestellung erhalten und werden sie bald bearbeiten.</p>
    <a href="index.php" class="btn btn-primary mt-4">Zurück zum Hauptmenü</a>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<?php include 'footer.php'; ?>
</body>
</html>

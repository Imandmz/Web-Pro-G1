<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$stmt = $pdo->prepare("SELECT last_login FROM kunden WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$lastLogin = $stmt->fetchColumn();
?>
<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8">
  <title>EnergyFans - Startseite</title>
  <link rel="stylesheet" href="assets/css/style.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include 'navbar.php'; ?>

<div class="container mt-5">
  <h1>Willkommen bei EnergyFans!</h1>
  <p>Hallo <?php echo htmlspecialchars($_SESSION['user_email']); ?>, Sie waren zuletzt am <?php echo date('d.m.Y H:i', strtotime($lastLogin)); ?> online.</p>

  <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-inner">
      <div class="carousel-item active">
        <img src="assets/images/energy1.jpg" class="d-block w-100" alt="Drink1">
      </div>
      <div class="carousel-item">
        <img src="assets/images/energy2.jpg" class="d-block w-100" alt="Drink2">
      </div>
      <div class="carousel-item">
        <img src="assets/images/energy3.jpg" class="d-block w-100" alt="Drink3">
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

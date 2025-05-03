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
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="assets/css/style.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<?php include 'navbar.php'; ?>

<div class="container container-box mt-5">
  <h1>Willkommen bei EnergyFans!</h1>
  <p>
    Hallo <?php echo htmlspecialchars($_SESSION['user_email']); ?>,<br>
    Sie waren zuletzt am <?php echo date('d.m.Y H:i', strtotime($lastLogin)); ?> online.
  </p>

<!-- Carousel automatisch aus Produkten -->
 <div id="productCarousel" class="carousel slide mt-4" data-bs-ride="carousel">
  <div class="carousel-inner">
    <?php
    $stmt = $pdo->query("SELECT * FROM produkte");
    $products = $stmt->fetchAll();
    $first = true;
    foreach ($products as $product): ?>
      <div class="carousel-item <?php if ($first) { echo 'active'; $first = false; } ?>">
        <img src="assets/images/<?php echo htmlspecialchars($product['bild']); ?>" class="d-block w-100" alt="<?php echo htmlspecialchars($product['name']); ?>">
      </div>
    <?php endforeach; ?>
  </div>

  <!-- Pfeile -->
  <button class="carousel-control-prev" type="button" data-bs-target="#productCarousel" data-bs-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Zurück</span>
  </button>
  <button class="carousel-control-next" type="button" data-bs-target="#productCarousel" data-bs-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Weiter</span>
  </button>
 </div>
</div>

<?php include 'footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


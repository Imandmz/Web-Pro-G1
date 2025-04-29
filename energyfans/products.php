<?php
session_start();
require_once 'config.php';

// Produkte aus der DB laden
$stmt = $pdo->query("SELECT * FROM produkte");
$products = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Produkte - EnergyFans</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include 'navbar.php'; ?>

<div class="container mt-5">
  <h1>Unsere Produkte</h1>
  <div class="row">
    <?php foreach ($products as $product): ?>
      <div class="col-md-4 mb-3">
        <div class="card">
          <img src="assets/images/<?php echo htmlspecialchars($product['bild']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($product['name']); ?>">
          <div class="card-body">
            <h5 class="card-title"><?php echo htmlspecialchars($product['name']); ?></h5>
            <p class="card-text"><?php echo number_format($product['preis'], 2, ',', '.'); ?> €</p>
            <form method="post" action="cart.php">
              <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
              <div class="input-group mb-2">
                  <input type="number" name="menge" value="1" min="1" class="form-control" required>
                  <button type="submit" name="add_to_cart" class="btn btn-primary">In den Warenkorb</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


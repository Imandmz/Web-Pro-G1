<?php
session_start();
require_once 'config.php';

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

<div class="container container-box mt-5">
  <h1 class="text-dark mb-4">Unsere Produkte</h1>
  <div class="row">
    <?php foreach ($products as $product): ?>
      <div class="col-sm-6 col-md-4 col-lg-3 mb-4">
        <div class="card h-100">
          <img src="assets/images/<?php echo htmlspecialchars($product['bild']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($product['name']); ?>">
          <div class="card-body d-flex flex-column">
            <h5 class="card-title"><?php echo htmlspecialchars($product['name']); ?></h5>

            <?php if (trim($product['name']) === 'Coming Soon'): ?>
              <p class="card-text text-muted"><em>4-Season EnergyFans</em></p>
            <?php else: ?>
              <p class="card-text"><?php echo number_format($product['preis'], 2, ',', '.'); ?> €</p>
              <form method="post" action="cart.php" class="mt-auto">
                <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                <input type="number" name="menge" value="1" min="1" class="form-control mb-2">
                <button type="submit" name="add_to_cart" class="btn btn-primary w-100">In den Warenkorb</button>
              </form>
            <?php endif; ?>

          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</div>

<?php include 'footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

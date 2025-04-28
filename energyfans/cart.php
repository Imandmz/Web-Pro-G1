<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Produkt hinzufügen oder Stückzahl erhöhen
if (isset($_POST['product_id'], $_POST['menge'])) {
    $productId = (int)$_POST['product_id'];
    $menge = (int)$_POST['menge'];

    $stmt = $pdo->prepare("SELECT id FROM warenkorb WHERE user_id = ? AND produkt_id = ?");
    $stmt->execute([$_SESSION['user_id'], $productId]);

    if ($stmt->rowCount() > 0) {
        $update = $pdo->prepare("UPDATE warenkorb SET menge = menge + ? WHERE user_id = ? AND produkt_id = ?");
        $update->execute([$menge, $_SESSION['user_id'], $productId]);
    } else {
        $insert = $pdo->prepare("INSERT INTO warenkorb (user_id, produkt_id, menge) VALUES (?, ?, ?)");
        $insert->execute([$_SESSION['user_id'], $productId, $menge]);
    }
}

// Warenkorb anzeigen
$stmt = $pdo->prepare("
SELECT p.name, p.preis, w.menge
FROM warenkorb w
JOIN produkte p ON w.produkt_id = p.id
WHERE w.user_id = ?
");
$stmt->execute([$_SESSION['user_id']]);
$cartItems = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8">
  <title>Warenkorb - EnergyFans</title>
  <link rel="stylesheet" href="assets/css/style.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include 'navbar.php'; ?>

<div class="container mt-5">
  <h1>Dein Warenkorb</h1>
  <?php if ($cartItems): ?>
    <table class="table">
      <thead>
        <tr>
          <th>Produkt</th>
          <th>Menge</th>
          <th>Preis</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $total = 0;
        foreach ($cartItems as $item):
          $preis = $item['preis'];
          if ($item['menge'] >= 5 && $item['menge'] < 10) {
              $preis *= 0.95;
          } elseif ($item['menge'] >= 10) {
              $preis *= 0.90;
          }
          $zeilenpreis = $preis * $item['menge'];
          $total += $zeilenpreis;
        ?>
          <tr>
            <td><?php echo htmlspecialchars($item['name']); ?></td>
            <td><?php echo $item['menge']; ?></td>
            <td><?php echo number_format($zeilenpreis, 2); ?> €</td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    <h3>Gesamt: <?php echo number_format($total, 2); ?> €</h3>
    <a href="checkout.php" class="btn btn-success">Zur Kasse</a>
  <?php else: ?>
    <p>Dein Warenkorb ist leer.</p>
  <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

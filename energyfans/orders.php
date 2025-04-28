<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$stmt = $pdo->prepare("
SELECT b.id, b.datum, b.gesamtpreis, b.versandart
FROM bestellungen b
WHERE b.user_id = ?
ORDER BY b.datum DESC
");
$stmt->execute([$_SESSION['user_id']]);
$orders = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8">
  <title>Bestellungen - EnergyFans</title>
  <link rel="stylesheet" href="assets/css/style.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include 'navbar.php'; ?>

<div class="container mt-5">
  <h1>Meine Bestellungen</h1>
  <?php if ($orders): ?>
    <table class="table">
      <thead>
        <tr>
          <th>Bestellnummer</th>
          <th>Datum</th>
          <th>Gesamtpreis</th>
          <th>Versandart</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($orders as $order): ?>
          <tr>
            <td><?php echo htmlspecialchars($order['id']); ?></td>
            <td><?php echo htmlspecialchars($order['datum']); ?></td>
            <td><?php echo number_format($order['gesamtpreis'], 2); ?> €</td>
            <td><?php echo htmlspecialchars($order['versandart']); ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php else: ?>
    <p>Keine Bestellungen gefunden.</p>
  <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

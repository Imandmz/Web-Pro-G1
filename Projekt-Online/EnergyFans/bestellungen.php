<?php
$stmt = $pdo->prepare("SELECT * FROM bestellungen WHERE user_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$orders = $stmt->fetchAll();
foreach ($orders as $order):
?>
  <div>
    <strong>Bestellung #<?= $order['id'] ?></strong>
    <form method="post" action="repeat_order.php">
      <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
      <button type="submit">Nochmal bestellen</button>
    </form>
  </div>
<?php endforeach; ?>

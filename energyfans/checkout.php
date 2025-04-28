<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Warenkorb laden
$stmt = $pdo->prepare("
SELECT p.id, p.name, p.preis, w.menge
FROM warenkorb w
JOIN produkte p ON w.produkt_id = p.id
WHERE w.user_id = ?
");
$stmt->execute([$_SESSION['user_id']]);
$cartItems = $stmt->fetchAll();

$total = 0;
foreach ($cartItems as $item) {
    $preis = $item['preis'];
    if ($item['menge'] >= 5 && $item['menge'] < 10) {
        $preis *= 0.95;
    } elseif ($item['menge'] >= 10) {
        $preis *= 0.90;
    }
    $total += $preis * $item['menge'];
}

// Bestellung abschließen
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['versandart'], $_POST['datenschutz'])) {
    $versandart = $_POST['versandart'];

    // Versandkosten
    if ($versandart == 'DHL') {
        $total += 4.5;
    } elseif ($versandart == 'Express') {
        $total += 10.5;
    } elseif ($versandart == 'LPD') {
        $total += 7.5;
    }

    $insert = $pdo->prepare("INSERT INTO bestellungen (user_id, gesamtpreis, versandart) VALUES (?, ?, ?)");
    $insert->execute([$_SESSION['user_id'], $total, $versandart]);
    $bestellungId = $pdo->lastInsertId();

    // Bestellpositionen speichern
    foreach ($cartItems as $item) {
        $preis = $item['preis'];
        if ($item['menge'] >= 5 && $item['menge'] < 10) {
            $preis *= 0.95;
        } elseif ($item['menge'] >= 10) {
            $preis *= 0.90;
        }
        $insertPos = $pdo->prepare("INSERT INTO bestellpositionen (bestellung_id, produkt_id, menge, einzelpreis) VALUES (?, ?, ?, ?)");
        $insertPos->execute([$bestellungId, $item['id'], $item['menge'], $preis]);
    }

    // Warenkorb leeren
    $pdo->prepare("DELETE FROM warenkorb WHERE user_id = ?")->execute([$_SESSION['user_id']]);

    header('Location: danke.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8">
  <title>Checkout - EnergyFans</title>
  <link rel="stylesheet" href="assets/css/style.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include 'navbar.php'; ?>

<div class="container mt-5">
  <h1>Checkout</h1>
  <form method="post" action="">
    <div class="mb-3">
      <label for="versandart" class="form-label">Versandart wählen:</label>
      <select class="form-select" name="versandart" required>
        <option value="DHL">DHL (4,50€)</option>
        <option value="Express">DHL Express (+6€)</option>
        <option value="LPD">LPD (3€ teurer als DHL)</option>
      </select>
    </div>
    <div class="form-check mb-3">
      <input class="form-check-input" type="checkbox" name="datenschutz" id="datenschutz" required>
      <label class="form-check-label" for="datenschutz">Ich akzeptiere die Datenschutzbestimmungen.</label>
    </div>
    <button type="submit" class="btn btn-success w-100">Bezahlen</button>
  </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

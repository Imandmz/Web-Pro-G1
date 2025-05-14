<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Warenkorb laden
$stmt = $pdo->prepare("
SELECT p.id AS produkt_id, p.name, p.preis, w.menge
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

// Punkte des Users laden
$stmt = $pdo->prepare("SELECT punkte FROM kunden WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$verfügbarePunkte = $stmt->fetchColumn();

// Punkte einlösen
$eingelöstePunkte = 0;
$rabatt = 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['punkte']) && is_numeric($_POST['punkte'])) {
    $eingelöstePunkte = min((int)$_POST['punkte'], $verfügbarePunkte);
    $rabatt = $eingelöstePunkte * 0.01; // 1 Punkt = 1 Cent
    $total -= $rabatt;
}

// Bestellung abschließen
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['versandart']) && isset($_POST['datenschutz'])) {
    $versandart = $_POST['versandart'];

    // Versandkosten dazu
    if ($versandart == 'DHL') {
        $total += 4.5;
    } elseif ($versandart == 'Express') {
        $total += 10.5;
    } elseif ($versandart == 'LPD') {
        $total += 7.5;
    }

    // Neue Bestellung anlegen
    $insertBestellung = $pdo->prepare("INSERT INTO bestellungen (user_id, gesamtpreis, versandart) VALUES (?, ?, ?)");
    $insertBestellung->execute([$_SESSION['user_id'], $total, $versandart]);
    $bestellungId = $pdo->lastInsertId();

    // Punkte gutschreiben nach Bestellung
    $pdo->prepare("UPDATE kunden SET punkte = punkte + 20 - ? WHERE id = ?")
        ->execute([$eingelöstePunkte, $_SESSION['user_id']]);

    // Punktehistorie eintragen
    $log = $pdo->prepare("INSERT INTO punkte (user_id, punkte, grund) VALUES (?, ?, ?)");
    $log->execute([$_SESSION['user_id'], 20, 'Einkauf']);
    if ($eingelöstePunkte > 0) {
        $log->execute([$_SESSION['user_id'], -$eingelöstePunkte, 'Einlösung im Checkout']);
    }

    // Bestellpositionen speichern
    foreach ($cartItems as $item) {
        $preis = $item['preis'];
        if ($item['menge'] >= 5 && $item['menge'] < 10) {
            $preis *= 0.95;
        } elseif ($item['menge'] >= 10) {
            $preis *= 0.90;
        }

        $insertPosition = $pdo->prepare("INSERT INTO bestellpositionen (bestellung_id, produkt_id, menge, einzelpreis) VALUES (?, ?, ?, ?)");
        $insertPosition->execute([$bestellungId, $item['produkt_id'], $item['menge'], $preis]);
    }

    // Warenkorb leeren
    $pdo->prepare("DELETE FROM warenkorb WHERE user_id = ?")->execute([$_SESSION['user_id']]);

    // Weiterleitung auf Danke-Seite
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

<div class="container container-box mt-5 text-center">
    <h1>Checkout</h1>

    <?php if ($cartItems): ?>
        <form method="post" action="">
            <div class="mb-3">
                <label for="versandart" class="form-label">Versandart wählen:</label>
                <select class="form-select" name="versandart" id="versandart" required>
                    <option value="DHL">DHL (4,50 €)</option>
                    <option value="Express">DHL Express (10,50 €)</option>
                    <option value="LPD">LPD (7,50 €)</option>
                </select>
            </div>

            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" id="datenschutz" name="datenschutz" required>
                <label class="form-check-label" for="datenschutz">
                    Ich akzeptiere die Datenschutzbestimmungen.
                </label>
            </div>

            <?php if ($verfügbarePunkte >= 10): ?>
                <div class="mb-3">
                    <label for="punkte" class="form-label">Punkte einlösen (max <?= $verfügbarePunkte ?>):</label>
                    <input type="number" name="punkte" id="punkte" class="form-control" min="0" max="<?= $verfügbarePunkte ?>" value="<?= htmlspecialchars($eingelöstePunkte) ?>">
                    <small class="text-muted">10 Punkte = 10 Cent</small>
                </div>
            <?php else: ?>
                <p class="text-muted">Du hast <?= $verfügbarePunkte ?> Punkte. Mindestmenge zum Einlösen: 10 Punkte.</p>
            <?php endif; ?>

            <?php if ($eingelöstePunkte > 0): ?>
                <div class="alert alert-info">
                    Eingelöste Punkte: <?= $eingelöstePunkte ?> → Rabatt: <?= number_format($rabatt, 2, ',', '.') ?> €
                </div>
            <?php endif; ?>

            <button type="submit" class="btn btn-success w-100">Bezahlen</button>
        </form>
    <?php else: ?>
        <p>Ihr Warenkorb ist leer. Bitte fügen Sie Produkte hinzu.</p>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<?php include 'footer.php'; ?>
</body>
</html>


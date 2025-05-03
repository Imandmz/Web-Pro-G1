<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) {
    header('Location: orders.php');
    exit();
}

$bestellungId = (int)$_GET['id'];

// Prüfen, ob Bestellung dem User gehört
$stmt = $pdo->prepare("SELECT * FROM bestellungen WHERE id = ? AND user_id = ?");
$stmt->execute([$bestellungId, $_SESSION['user_id']]);
$bestellung = $stmt->fetch();

if (!$bestellung) {
    echo "Bestellung nicht gefunden oder Zugriff verweigert.";
    exit();
}

// Bestellpositionen laden
$stmt = $pdo->prepare("
SELECT p.name, p.bild, bp.menge, bp.einzelpreis
FROM bestellpositionen bp
JOIN produkte p ON bp.produkt_id = p.id
WHERE bp.bestellung_id = ?
");
$stmt->execute([$bestellungId]);
$produkte = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Bestelldetails</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
        background-image: url('assets/images/background.jpg');
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
        margin-bottom: 300px; /* Noch mehr Abstand zum Footer */
    }

    .container-box {
        background-color: rgba(255, 255, 255, 0.92);
        padding: 30px;
        border-radius: 15px;
        box-shadow: 0 0 12px rgba(0,0,0,0.3);
        margin-bottom: 100px; /* Noch mehr Abstand zwischen Inhalt und Footer */
    }

    .produkt-block {
        border: 1px solid #ccc;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        background-color: #f8f9fa;
    }

    .produkt-block img {
        width: 80px;
        height: auto;
        margin-right: 15px;
    }

    .details-label {
        font-weight: bold;
    }

    /* Button anpassen */
    .btn-back {
        margin-top: 20px;
    }
  </style>
</head>
<body>
<?php include 'navbar.php'; ?>

<div class="container mt-5 container-box">
    <h2>Bestellung #<?= htmlspecialchars($bestellung['id']) ?></h2>
    <p><span class="details-label">Datum:</span> <?= htmlspecialchars($bestellung['datum']) ?></p>
    <p><span class="details-label">Versandart:</span> <?= htmlspecialchars($bestellung['versandart']) ?></p>

    <h4 class="mt-4">Produkte</h4>
    <?php foreach ($produkte as $produkt): ?>
        <div class="produkt-block">
            <img src="assets/images/<?= htmlspecialchars($produkt['bild']) ?>" alt="<?= htmlspecialchars($produkt['name']) ?>">
            <div>
                <div><strong><?= htmlspecialchars($produkt['name']) ?></strong></div>
                <div>Menge: <?= $produkt['menge'] ?></div>

                <?php
                $originalPreis = $produkt['einzelpreis'];
                $rabattText = '';
                if ($produkt['menge'] >= 10) {
                    $originalPreis *= 1.11;
                    $rabattText = ' (10% Rabatt)';
                } elseif ($produkt['menge'] >= 5) {
                    $originalPreis *= 1.05;
                    $rabattText = ' (5% Rabatt)';
                }
                ?>
                <div>Einzelpreis: <?= number_format($produkt['einzelpreis'], 2) ?> €<?= $rabattText ?></div>
                <div>Zwischensumme: <?= number_format($produkt['einzelpreis'] * $produkt['menge'], 2) ?> €</div>
            </div>
        </div>
    <?php endforeach; ?>

    <?php
    // Versandkosten berechnen
    $versandkosten = 0;
    switch ($bestellung['versandart']) {
        case 'DHL':
            $versandkosten = 4.50;
            break;
        case 'Express':
            $versandkosten = 10.50;
            break;
        case 'LPD':
            $versandkosten = 7.50;
            break;
    }

    // Summe Produkte
    $summeProdukte = 0;
    foreach ($produkte as $produkt) {
        $summeProdukte += $produkt['einzelpreis'] * $produkt['menge'];
    }

    // Gesamtbetrag berechnen
    $gesamtbetrag = $summeProdukte + $versandkosten;
    ?>

    <hr>
    <p><span class="details-label">Zwischensumme (Produkte):</span> <?= number_format($summeProdukte, 2) ?> €</p>
    <p><span class="details-label">Versandkosten:</span> <?= number_format($versandkosten, 2) ?> €</p>
    <p class="fw-bold"><span class="details-label">Gesamtbetrag:</span> <?= number_format($gesamtbetrag, 2) ?> €</p>

    <!-- "Zurück" Button -->
    <a href="orders.php" class="btn btn-secondary mt-3 btn-back">Zurück</a>

</div>

<!-- Footer -->
<?php include 'footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) {
    header('Location: orders.php');
    exit();
}

$bestellungId = (int)$_GET['id'];

// Punkte laden
$punkte = 0;
if (!empty($_SESSION['user_id'])) {
    $stmt = $pdo->prepare("SELECT punkte FROM kunden WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $punkte = $stmt->fetchColumn();
}

// Bestellung prüfen
$stmt = $pdo->prepare("SELECT * FROM bestellungen WHERE id = ? AND user_id = ?");
$stmt->execute([$bestellungId, $_SESSION['user_id']]);
$bestellung = $stmt->fetch();

if (!$bestellung) {
    echo "Bestellung nicht gefunden oder Zugriff verweigert.";
    exit();
}

// Produkte der Bestellung laden
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
            margin-bottom: 300px;
        }

        .container-box {
            background-color: rgba(255, 255, 255, 0.92);
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 0 12px rgba(0,0,0,0.3);
            margin-bottom: 100px;
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

        .btn-back {
            margin-top: 20px;
        }

        /* ==== NAVBAR FARBE ANPASSEN ==== */
        .navbar {
            font-family: 'sans-serif; /* Schriftart ändern */
            font-size: 10 px; /* Schriftgröße anpassen */
            font-weight: bold; /* Schrift fett machen */
        }

        .navbar a,
        .navbar-nav .nav-link {
            color: white !important;
            transition: color 0.3s ease;
        }

        .navbar a:hover,
        .navbar-nav .nav-link:hover {
            color: yellow !important;
        }

        .navbar {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 1030; /* Stellt sicher, dass die Navbar über anderen Inhalten bleibt */
        }

        body {
            padding-top: 56px; /* Abstand nach unten, damit der Inhalt nicht hinter der Navbar verschwindet */
        }
    </style>
</head>
<body>

<!-- Neue NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <!-- Logo + EnergyFans = Link zur Projektinfo -->
    <a class="navbar-brand d-flex align-items-center" href="projektinfo.php" title="Projektinformationen anzeigen">
      <img src="assets/images/logo.png" alt="Logo" height="40" class="me-2">
      <span>EnergyFans</span>
    </a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto align-items-center">
        <li class="nav-item"><a class="nav-link" href="index.php"> Main-Menu</a></li>
        <li class="nav-item"><a class="nav-link" href="products.php"> Produkte</a></li>
        <li class="nav-item"><a class="nav-link" href="cart.php"> Warenkorb</a></li>
        <li class="nav-item"><a class="nav-link" href="orders.php"> Bestellungen</a></li>

        <?php if (!empty($_SESSION['user_id'])): ?>
          <li class="nav-item">
            <span class="nav-link text-warning"> Punkte: <?= htmlspecialchars($punkte) ?></span>
          </li>
          <li class="nav-item">
            <span class="nav-link text-info" id="online-users"> Online: …</span>
          </li>
        <?php endif; ?>

        <li class="nav-item"><a class="nav-link" href="logout.php"> Logout</a></li>
      </ul>
    </div>
  </div>
</nav>

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
                $rabattText = '';
                if ($produkt['menge'] >= 15) {
                    $rabattText = ' (15% Rabatt)';
                } elseif ($produkt['menge'] >= 10) {
                    $rabattText = ' (10% Rabatt)';
                } elseif ($produkt['menge'] >= 5) {
                    $rabattText = ' (5% Rabatt)';
                }
                ?>
                <div>Einzelpreis: <?= number_format($produkt['einzelpreis'], 2) ?> €<?= $rabattText ?></div>
                <div>Zwischensumme: <?= number_format($produkt['einzelpreis'] * $produkt['menge'], 2) ?> €</div>
            </div>
        </div>
    <?php endforeach; ?>

    <?php
    $versandkosten = match ($bestellung['versandart']) {
        'DHL' => 4.50,
        'Express' => 10.50,
        'LPD' => 7.50,
        'Standard' => 5.00, // Neue Versandart hinzugefügt
        default => 0,
    };
    $summeProdukte = array_sum(array_map(fn($p) => $p['einzelpreis'] * $p['menge'], $produkte));
    $gesamtbetrag = $summeProdukte + $versandkosten;
    ?>

    <hr>
    <p><span class="details-label">Zwischensumme (Produkte):</span> <?= number_format($summeProdukte, 2) ?> €</p>
    <p><span class="details-label">Versandkosten:</span> <?= number_format($versandkosten, 2) ?> €</p>
    <p class="fw-bold"><span class="details-label">Gesamtbetrag:</span> <?= number_format($gesamtbetrag, 2) ?> €</p>

    <a href="orders.php" class="btn btn-secondary mt-3 btn-back">Zurück</a>
</div>

<?php include 'footer.php'; ?>

<?php if (!empty($_SESSION['user_id'])): ?>
<script>
function updateOnlineStatus() {
  fetch('count_online.php')
    .then(res => res.text())
    .then(data => {
      const target = document.getElementById('online-users');
      if (!isNaN(data)) {
        target.textContent = 'Online: ' + data;
      } else {
        target.textContent = 'Online: –';
      }
    })
    .catch(() => {
      document.getElementById('online-users').textContent = 'Online: –';
    });

  fetch('update_online.php').catch(err => console.error('Fehler bei update_online:', err));
}

updateOnlineStatus();
setInterval(updateOnlineStatus, 10000);
</script>
<?php endif; ?>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>
</html>

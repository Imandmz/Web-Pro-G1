<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'config.php';

$punkte = 0;
if (!empty($_SESSION['user_id'])) {
    $stmt = $pdo->prepare("SELECT punkte FROM kunden WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $punkte = $stmt->fetchColumn();
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>EnergyFans</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .navbar {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        z-index: 1030;
    }
    body {
        padding-top: 56px;
    }
  </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand d-flex align-items-center" href="projektinfo.php">
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
            <span class="nav-link text-warning">Punkte: <?= htmlspecialchars($punkte) ?></span>
          </li>
          <li class="nav-item">
            <span class="nav-link text-info" id="online-users">Online: –</span>
          </li>
        <?php endif; ?>

        <li class="nav-item"><a class="nav-link" href="logout.php"> Logout</a></li>
      </ul>
    </div>
  </div>
</nav>

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

    fetch('update_online.php')
      .catch(err => console.error('Fehler bei update_online:', err));
  }

  updateOnlineStatus();
  setInterval(updateOnlineStatus, 10000);
</script>
<?php endif; ?>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>
</html>

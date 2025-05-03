<?php
// Footer nicht anzeigen in iFrames (z. B. tabletview oder mobileview)
if (
    basename($_SERVER['PHP_SELF']) === 'mobileview.php' ||
    basename($_SERVER['PHP_SELF']) === 'dashboard.php' ||
    (isset($_SERVER['HTTP_SEC_FETCH_DEST']) && $_SERVER['HTTP_SEC_FETCH_DEST'] === 'iframe')
) {
    return;
}
?>

<footer class="footer bg-dark text-white text-center py-3 fixed-bottom">
  <div class="container">
    <div class="d-flex justify-content-between align-items-center flex-wrap">
      <div>
        Projekt: <strong>EnergyFans</strong> – Erstellt von <strong>Gruppe-1-Web-Pro</strong>
      </div>
      <div class="d-flex flex-wrap justify-content-center gap-3 mt-2 mt-md-0">
        <a href="tabletview.php" class="text-warning text-decoration-none">📲 Tablet</a>
        <a href="index.php" class="text-info text-decoration-none">🖥️ PC-Version</a>
      </div>
    </div>
  </div>
</footer>



<?php
require 'config.php';
$stmt = $pdo->prepare("SELECT punkte, grund, datum FROM punkte WHERE user_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$rows = $stmt->fetchAll();
?>
<h2>Punkteverlauf</h2>
<table>
<tr><th>Punkte</th><th>Grund</th><th>Datum</th></tr>
<?php foreach ($rows as $row): ?>
<tr>
  <td><?= $row['punkte'] ?></td>
  <td><?= $row['grund'] ?></td>
  <td><?= $row['datum'] ?></td>
</tr>
<?php endforeach; ?>
</table>

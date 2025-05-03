<?php
require 'config.php';
$name = $_GET['email'] ?? '';
$stmt = $pdo->prepare("SELECT id FROM kunden WHERE email = ?");
$stmt->execute([$name]);
echo $stmt->rowCount() > 0 ? 'belegt' : 'frei';

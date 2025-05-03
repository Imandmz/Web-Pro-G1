<?php
require 'config.php';

$stmt = $pdo->query("SELECT COUNT(*) FROM online WHERE timestamp > NOW() - INTERVAL 60 SECOND");
echo $stmt->fetchColumn();

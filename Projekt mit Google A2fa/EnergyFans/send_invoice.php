<?php
use PHPMailer\PHPMailer\PHPMailer;
require 'vendor/autoload.php';

$mail = new PHPMailer();
$mail->isSMTP();
// ... SMTP-Konfig hier
$mail->addAddress($userEmail);
$mail->Subject = "Deine Rechnung - EnergyFans";
$mail->isHTML(true);
$mail->Body = "<h2>Rechnung</h2>... alle Bestelldetails ...";
$mail->send();

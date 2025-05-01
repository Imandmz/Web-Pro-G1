<?php session_start(); ?>
<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8">
  <title>Projektinfo – EnergyFans</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/style.css">
  <style>
    body {
      background-image: url('assets/images/kontakt.jpg');
      background-size: cover;
      background-position: center;
      background-repeat: no-repeat;
      color: white;
    }
    .info-box {
      background-color: rgba(0, 0, 0, 0.7);
      padding: 30px;
      border-radius: 15px;
      margin-top: 100px;
    }
    a {
      color: #0dcaf0;
      text-decoration: underline;
    }
  </style>
</head>
<body>

<?php include 'navbar.php'; ?>

 <div class="container info-box text-center">

  <h2>WIB3.6 – Web-Programmierung + Praktikum (S25)</h2>
  <p class="mb-4">Lehrveranstaltung bei Prof. Dr. Gutbrod</p>


  <h3> Webshop-Projekt: <strong>EnergyFans</strong></h3>
  <p><a href="http://localhost/energyfans/index.php" target="_blank">http://localhost/energyfans/index.php</a></p>


  <h4 class="mt-4"> Gruppenmitglieder</h4>
  <ul class="list-unstyled">



    <li><strong>Christian Schönfeld</strong> – <a href="mailto:Christian.Schoenfeld@Student.Reutlingen-University.DE">Christian.Schoenfeld@Student.Reutlingen-University.DE</a></li>

    <li><strong>Iman Daemalzekr</strong> – <a href="mailto:Iman.Daemalzekr@Student.Reutlingen-University.DE">Iman.Daemalzekr@Student.Reutlingen-University.DE</a></li>


    <li><strong>Sebastian Lupu</strong> – <a href="mailto:Sebastian.Lupu@Student.Reutlingen-University.DE">Sebastian.Lupu@Student.Reutlingen-University.DE</a></li>
  </ul>



  <a href="index.php" class="btn btn-warning mt-4">Zurück zum Hauptmenü</a>
</div>

<?php include 'footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

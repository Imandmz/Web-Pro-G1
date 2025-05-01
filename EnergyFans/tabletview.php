<?php session_start(); ?>
<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8">
  <title>EnergyFans – Tablet Vorschau</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    body {
      background: #111;
      color: white;
      padding: 40px 10px;
      text-align: center;
      font-family: Arial, sans-serif;
    }

    .device-frame {
      width: 820px;
      height: auto;
      margin: auto;
      border: 18px solid #333;
      border-radius: 30px;
      box-shadow: 0 0 25px rgba(0,0,0,0.5);
      overflow: hidden;
      background: black;
      padding-bottom: 20px;
    }

    .device-frame iframe {
      width: 100%;
      height: 1000px;
      border: none;
    }

    .btn-return {
      margin-top: 15px;
    }
  </style>
</head>
<body>

  <h2>📲 Tablet Vorschau: EnergyFans</h2>

  <div class="device-frame">
    <iframe src="index.php"></iframe>

    <div class="btn-return">
      <a href="index.php" class="btn btn-warning mt-0" style="color: black;">PC-Version</a>
    </div>
  </div>

</body>
</html>


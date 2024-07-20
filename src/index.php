<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Maryouma Bank</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <style>
    body {
      font-family: 'Arial', sans-serif;
      background: linear-gradient(135deg, #e0f2f1, #b2dfdb);
      margin: 0;
      padding: 0;
    }

    .container {
      background: #ffffff;
      padding: 40px;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    }

    .btn-primary {
      background-color: #00796b;
      border: none;
      border-radius: 50px;
      padding: 12px 24px;
      transition: background-color 0.3s ease;
    }

    .btn-primary:hover {
      background-color: #004d40;
    }

    h2 {
      color: #004d40;
      font-weight: bold;
    }

    h3 {
      color: #00796b;
      margin-bottom: 20px;
    }

    p {
      color: #004d40;
      font-size: 18px;
    }

    .image-container {
      position: relative;
    }

    .circle {
      position: absolute;
      width: 120px;
      height: 120px;
      background-color: #00796b;
      border-radius: 50%;
      top: -60px;
      right: -60px;
      z-index: -1;
    }

    .square {
      position: absolute;
      width: 180px;
      height: 180px;
      background-color: #b2dfdb;
      bottom: -90px;
      left: -90px;
      z-index: -1;
    }
  </style>
</head>
<body>
  <?php include 'header.php'; ?>

  <div class="container py-4 d-flex justify-content-center">
    <div class="row align-items-center">
      <div class="col-md-6">
        <h2>Maryouma Bank</h2>
        <h3>Transaction Made Easy!</h3>
        <p>Transfer money directly with Maryouma Bank's seamless services.</p>
        <!-- <a href="transfer.php" class="btn btn-primary">Learn More</a> -->
      </div>
      <div class="col-md-6 image-container">
        <img src="images/bank.webp" width="350" alt="">
        <div class="circle"></div>
        <div class="square"></div>
      </div>
    </div>
  </div>
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Create User - Flare Bank</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <style>
    body {
      font-family: 'Arial', sans-serif;
      background: linear-gradient(135deg, #f8f9fa, #e9ecef);
    }

    .container {
      margin-top: 50px;
    }

    .card {
      background: #fff;
      border: none;
      border-radius: 10px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .card-body {
      position: relative;
    }

    .card-title {
      font-weight: bold;
      color: #343a40;
    }

    .form-label {
      font-weight: bold;
    }

    .form-control {
      border-radius: 50px;
    }

    .btn-create {
      background-color: #28a745;
      border: none;
      border-radius: 50px;
      padding: 10px 20px;
      transition: background-color 0.3s ease;
    }

    .btn-create:hover {
      background-color: #218838;
    }

    .btn-reset {
      background-color: #6c757d;
      border: none;
      border-radius: 50px;
      padding: 10px 20px;
      transition: background-color 0.3s ease;
    }

    .btn-reset:hover {
      background-color: #5a6268;
    }

    .alert {
      border-radius: 50px;
    }

    .circle {
      position: absolute;
      width: 150px;
      height: 150px;
      background-color: #007bff;
      border-radius: 50%;
      top: -75px;
      left: -75px;
      z-index: -1;
    }

    .triangle {
      position: absolute;
      width: 0;
      height: 0;
      border-left: 75px solid transparent;
      border-right: 75px solid transparent;
      border-bottom: 130px solid #e9ecef;
      bottom: -65px;
      right: -75px;
      z-index: -1;
    }
  </style>
</head>
<body>
  <?php include 'header.php'; ?>

  <div class="container my-5">
    <?php if (!empty($message)) : ?>
      <div class="alert <?php echo (strpos($message, 'Successfully') !== false) ? 'alert-success' : 'alert-danger'; ?>" role="alert">
        <?php echo htmlspecialchars($message); ?>
      </div>
    <?php endif; ?>
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title mb-4">Create User</h5>
            <form method="post" action="createUser.php">
              <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control" id="name" name="name" required>
              </div>
              <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
              </div>
              <div class="mb-3">
                <label for="balance" class="form-label">Balance</label>
                <input type="number" class="form-control" id="balance" name="balance" required>
              </div>
              <button type="submit" class="btn btn-create" name="submit" >Create</button>
              <button type="reset" class="btn btn-reset">Reset</button>
            </form>
            <div class="circle"></div>
            <div class="triangle"></div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <?php include 'footer.php'; ?>
</body>
</html>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maryouma Bank</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://kit.fontawesome.com/c98f2fbcc1.js" crossorigin="anonymous"></script>
    <style>
        /* Header styles */
        header {
            background-color: #004d40; /* Dark teal background for header */
            color: #ffffff; /* White text color */
            border-bottom: 3px solid #00acc1; /* Teal bottom border */
        }

        .navbar-brand h2 {
            color: #ffffff; /* White text color for brand */
            font-weight: bold; /* Bold text */
        }

        /* Custom styles for navigation links */
        .navbar-nav .nav-link {
            font-weight: 500; /* Medium weight font */
            padding: 10px 15px; /* Padding for better spacing */
        }

        .navbar-nav .nav-link.home {
            color: #ffeb3b; /* Yellow color for Home */
        }

        .navbar-nav .nav-link.create-user {
            color: #ff5722; /* Orange color for Create User */
        }

        .navbar-nav .nav-link.transfer {
            color: #4caf50; /* Green color for Transfer */
        }

        .navbar-nav .nav-link.transaction-history {
            color: #2196f3; /* Blue color for Transaction History */
        }

        /* Hover and active states */
        .navbar-nav .nav-link:hover {
            text-decoration: underline; /* Underline on hover */
        }

        .navbar-nav .nav-link.active {
            color: #ffffff; /* White color for active link */
            background-color: #00796b; /* Darker teal background for active link */
            border-radius: 5px; /* Slightly rounded corners for active link background */
        }
    </style>
</head>

<body>
    <header class="navbar navbar-expand-lg navbar-light">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <h2>Maryouma Bank</h2>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item"><a class="nav-link home" href="index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link create-user" href="createUser.php">Create User</a></li>
                    <li class="nav-item"><a class="nav-link transfer" href="transfer.php">Transfer</a></li>
                    <li class="nav-item"><a class="nav-link transaction-history" href="transactionHistory.php">Transaction History</a></li>
                </ul>
            </div>
        </div>
    </header>
</body>
</html>

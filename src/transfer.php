<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transfer Money - Maryouma Bank</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        body {
            background-color: #e0f7fa; /* Light cyan background for the page */
            font-family: 'Arial', sans-serif;
        }

        .container {
            background-color: #ffffff; /* White background for the container */
            padding: 30px;
            border-radius: 12px; /* Rounded corners */
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2); /* Shadow effect */
        }

        .custom-table {
            background-color: #ffffff; /* White background for the table */
            border-collapse: collapse;
            width: 100%;
        }

        .custom-table th, .custom-table td {
            border: 1px solid #cccccc; /* Light grey border */
            padding: 12px;
        }

        .custom-table th {
            background-color: #004d40; /* Dark teal background for table headers */
            color: #ffffff; /* White text color for headers */
        }

        .custom-table tr:nth-child(even) {
            background-color: #f2f2f2; /* Light grey background for even rows */
        }

        .custom-table tr:hover {
            background-color: #e0f2f1; /* Light teal background for row on hover */
        }

        .heading {
            color: #004d40; /* Dark teal color for heading */
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <?php include 'header.php'; ?>

    <div class="container my-5">
        <div class="table-container">
            <h1 class="heading">Transfer Money</h1>
            <table class="table custom-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Sender</th>
                        <th>Receiver</th>
                        <th>Amount</th>
                        <th>Date</th>
                    </tr>
                </thead>
                
            </table>
        </div>
    </div>
</body>
</html>

<?php
require_once "config.php"; // Database connection

// Fetch all intruder attempts
$sql = "SELECT id, username, attempt_time, attempt_count FROM intruders ORDER BY attempt_time DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$intruders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Intruder Log</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #121212; /* Dark background */
            color: #ffffff; /* Light text */
        }
        .nav-logo {
            width: 50px;
            height: 50px;
        }
        .navbar {
            background-color: #1c1c1c; /* Darker navbar */
        }
        .btn-primary {
            background-color: #007bff; /* Blue button */
            border: none;
        }
        .btn-primary:hover {
            background-color: #0056b3; /* Darker blue on hover */
        }
        .table {
            background-color: #1e1e1e; /* Table background */
            color: #ffffff; /* Table text color */
        }
        .table th {
            background-color: #007bff; /* Blue header */
            color: #ffffff; /* White header text */
        }
        .table td {
            background-color: #1e1e1e; /* Table data background */
        }
        .table tbody tr:hover {
            background-color: #333333; /* Hover effect on rows */
        }
        h2, h5 {
            color: #007bff; /* Blue headings */
        }
        .book-card {
            background-color: #1e1e1e; /* Card background */
            border-radius: 5px; /* Rounded corners */
            padding: 15px;
            margin: 10px; /* Spacing between cards */
            transition: transform 0.3s; /* Smooth scale effect */
        }
        .book-card:hover {
            transform: scale(1.05); /* Scale up on hover */
        }
        .book-cover {
            width: 150px; /* Fixed width for cover images */
            height: 225px; /* Fixed height for cover images */
            object-fit: cover; /* Ensures images maintain aspect ratio */
            border-radius: 5px; /* Rounded corners for images */
        }
        .collapse {
            margin-top: 15px; /* Spacing above collapse sections */
        }
        .row {
            margin-top: 20px; /* Spacing for the row */
        }
    </style> 
</head>
<body>
    
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="#"><img src="logo.png" class="nav-logo" alt="Logo"></a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item"><a class="nav-link" href="joindata.php">Join</a></li>
                <li class="nav-item"><a class="nav-link" href="dashboard.php">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="warning.php">warning</a></li>
                <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
            </ul>
        </div>
    </nav>
</nav>
    <div class="container">
        <h2 class="text-center">Intruder Attempt Logs</h2>
        <table class="table table-bordered table-striped">
            <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Attempt Count</th>
                    <th>Last Attempt Time</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($intruders) > 0): ?>
                    <?php foreach ($intruders as $intruder): ?>
                        <tr>
                            <td><?= htmlspecialchars($intruder['id']); ?></td>
                            <td><?= htmlspecialchars($intruder['username']); ?></td>
                            <td><?= htmlspecialchars($intruder['attempt_count']); ?></td>
                            <td><?= htmlspecialchars($intruder['attempt_time']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="text-center">No intruder attempts found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>

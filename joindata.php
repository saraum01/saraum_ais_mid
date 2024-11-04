<?php
session_start();
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

require_once "config.php"; // Database connection

// Fetching data using LEFT JOIN for authors and books
$leftJoinQuery = $pdo->query("
    SELECT a.name AS author, b.title 
    FROM authors a
    LEFT JOIN books b ON a.id = b.author_id
")->fetchAll(PDO::FETCH_ASSOC);

// Fetching data using RIGHT JOIN for books and genres
$rightJoinQuery = $pdo->query("
    SELECT b.title, g.genre_name 
    FROM books b
    RIGHT JOIN genres g ON b.genre_id = g.id
")->fetchAll(PDO::FETCH_ASSOC);

// Fetching data using UNION for authors, books, and genres with separate columns
$unionQuery = $pdo->query("
    SELECT b.title AS book, a.name AS author, g.genre_name AS genre
    FROM books b
    LEFT JOIN authors a ON b.author_id = a.id
    LEFT JOIN genres g ON b.genre_id = g.id
")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Join Data Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .nav-logo {
            width: 50px;
            height: 50px;
        }
    </style> <style>
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
        .collapse {
            margin-top: 15px; /* Spacing above collapse sections */
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="#"><img src="logo.png" class="nav-logo" alt="Logo"></a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item"><a class="nav-link" href="dashboard.php">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="joindata.php">Join Data</a></li>
                <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
            </ul>
        </div>
    </nav>

    <div class="container mt-4">
        <h2>Join Data Dashboard</h2>
        <div class="mb-3">
            <button class="btn btn-primary" data-toggle="collapse" data-target="#leftJoinData">Show Authors and Books</button>
            <button class="btn btn-success" data-toggle="collapse" data-target="#rightJoinData">Show Books and Genres</button>
            <button class="btn btn-info" data-toggle="collapse" data-target="#unionData">Show Authors, Books, and Genres</button>
        </div>

        <div id="leftJoinData" class="collapse">
            <h5>Authors with their Books (LEFT JOIN)</h5>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Author</th>
                        <th>Book Title</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($leftJoinQuery as $row): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['author']); ?></td>
                            <td><?php echo htmlspecialchars($row['title'] ?: 'No Book'); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div id="rightJoinData" class="collapse">
            <h5>Books with their Genres (RIGHT JOIN)</h5>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Book Title</th>
                        <th>Genre</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($rightJoinQuery as $row): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['title'] ?: 'No Title'); ?></td>
                            <td><?php echo htmlspecialchars($row['genre_name'] ?: 'No Genre'); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div id="unionData" class="collapse">
            <h5>Combined List of Authors, Books, and Genres (UNION)</h5>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Book Title</th>
                        <th>Author</th>
                        <th>Genre</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($unionQuery as $item): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($item['book'] ?: 'No Title'); ?></td>
                            <td><?php echo htmlspecialchars($item['author'] ?: 'No Author'); ?></td>
                            <td><?php echo htmlspecialchars($item['genre'] ?: 'No Genre'); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

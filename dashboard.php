<?php
session_start();
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

require_once "config.php"; // Database connection

// Fetching data using LEFT JOIN to get books and their authors
$booksQuery = $pdo->query("
    SELECT b.title, a.name AS author, b.cover_image 
    FROM books b
    LEFT JOIN authors a ON b.author_id = a.id
")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Online Bookstore Dashboard</title>
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

    <div class="container mt-4">
        <h2>Books</h2>
        <h5>List of Books with Authors</h5>
        <div class="row">
            <?php foreach ($booksQuery as $book): ?>
                <div class="col-md-3"> <!-- Four books per row (4x4 grid) -->
                    <div class="book-card text-center">
                        <img src="<?php echo htmlspecialchars($book['cover_image']); ?>" class="book-cover" alt="Book Cover">
                        <h6 class="mt-2"><?php echo htmlspecialchars($book['title']); ?></h6>
                        <p class="text-muted"><?php echo htmlspecialchars($book['author'] ?: 'No Author'); ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

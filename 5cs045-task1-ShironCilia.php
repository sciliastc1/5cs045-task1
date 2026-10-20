<?php
//5cs045-task1-ShironCilia
/* 
This is a simple PHP script to connect to a MySQL database and 
display a list of books in an HTML table. 
It uses PDO for database interactions, which provides a secure way 
to handle database operations and helps helps to prevent SQL injection attacks.
*/
// Database connection settings
$host    = 'localhost';
$db      = 'Books';
// Dedicated database user username and password
$user    = 'books_app_user';
$pass    = 'Password123!';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Throw exceptions on errors
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Fetch associative arrays
    PDO::ATTR_EMULATE_PREPARES   => false,                  // Use real prepared statements
];

// Connect to the database
try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    error_log($e->getMessage()); // Log the real error server-side
    die("Could not connect to the database. Please try again later.");
}

// Fetch all books from the database
try {
    $stmt = $pdo->query("SELECT Book_name, Genre, Price, Date_of_release FROM books");
    $books = $stmt->fetchAll();
} catch (\PDOException $e) {
    error_log($e->getMessage()); // Log the real error server-side
    die("Could not retrieve books. Please try again later.");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Books List</title>

<!-- Basic styling for the page -->
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        h1 {
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ccc;
        }
        th {
            background-color: #f2f2f2;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        p {
            font-size: 16px;
            color: #666;
        }
    </style>
</head>
<body>
    <h1>Books List</h1>

    <!-- Display the books in a table. Before the table is listed the database
         is queried and checked if there are any results -->
    <?php if (!empty($books)): ?>
        <table>
            <thead>
                <tr>
                    <th>Book Name</th>
                    <th>Genre</th>
                    <th>Price</th>
                    <th>Date of Release</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($books as $book): ?>
                    <tr>
                        <td><?= htmlspecialchars($book['Book_name']) ?></td>
                        <td><?= htmlspecialchars($book['Genre']) ?></td>
                        <td>£<?= number_format((float)$book['Price'], 2) ?></td>
                        <td><?= !empty($book['Date_of_release']) ? date('d/m/Y', strtotime($book['Date_of_release'])) : 'N/A' ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <!-- If no books are found, display a message -->
        <p>No books found in the database.</p>
    <?php endif; ?>
</body>
</html>

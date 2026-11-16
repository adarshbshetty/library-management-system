<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// DATABASE CONNECTION
$host = "localhost";
$user = "root";
$password = "";
$database = "library_db";

$conn = mysqli_connect($host, $user, $password, $database);

if (!$conn) {
    die("Database Connection Failed: " . mysqli_connect_error());
}

// FETCH REPORT DATA
$totalBooks = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) AS total FROM books")
)['total'] ?? 0;

$totalStudents = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) AS total FROM students")
)['total'] ?? 0;

$totalIssued = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) AS total FROM issued_books")
)['total'] ?? 0;
?>

<!DOCTYPE html>
<html>
<head>
    <title>Library Reports</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f9;
            margin: 0;
            padding: 30px;
        }

        h1 {
            color: #222;
        }

        .container {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
        }

        .card {
            background: white;
            padding: 30px;
            width: 220px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .card h3 {
            color: #555;
        }

        .card p {
            font-size: 30px;
            font-weight: bold;
            color: #2563eb;
        }

        .back {
            display: inline-block;
            margin-bottom: 25px;
            background: #2563eb;
            color: white;
            padding: 10px 18px;
            text-decoration: none;
            border-radius: 5px;
        }
    </style>
</head>

<body>

<a class="back" href="dashboard.php">Back to Dashboard</a>

<h1>Library Reports</h1>

<div class="container">

    <div class="card">
        <h3>Total Books</h3>
        <p><?php echo $totalBooks; ?></p>
    </div>

    <div class="card">
        <h3>Total Students</h3>
        <p><?php echo $totalStudents; ?></p>
    </div>

    <div class="card">
        <h3>Issued Books</h3>
        <p><?php echo $totalIssued; ?></p>
    </div>

</div>

</body>
</html>

<?php mysqli_close($conn); ?>
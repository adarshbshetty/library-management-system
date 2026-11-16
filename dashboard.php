<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Management Dashboard</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            background: #f4f6f9;
        }

        header {
            background: #2056a5;
            color: white;
            padding: 22px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        header h2 {
            font-size: 22px;
        }

        header a {
            color: white;
            text-decoration: none;
            font-weight: bold;
        }

        .container {
            padding: 35px;
        }

        .container h1 {
            margin-bottom: 12px;
            color: #222;
        }

        .container p {
            color: #777;
            margin-bottom: 25px;
        }

        .cards {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
        }

        .card {
            background: white;
            padding: 30px 20px;
            border-radius: 10px;
            text-decoration: none;
            color: #222;
            box-shadow: 0 3px 10px rgba(0,0,0,0.08);
            transition: 0.3s;
            display: block;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 15px rgba(0,0,0,0.15);
            background: #eaf1ff;
        }

        .card h3 {
            margin-bottom: 12px;
            color: #2056a5;
        }

        .card p {
            margin: 0;
            color: #666;
        }

        @media (max-width: 800px) {
            .cards {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 500px) {
            .cards {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>

<header>
    <h2>Library Management System</h2>
    <a href="logout.php">Logout</a>
</header>

<div class="container">

    <h1>Welcome to Dashboard!</h1>
    <p>Manage your library easily.</p>

    <div class="cards">

        <!-- Books -->
        <a href="admin/books.php" class="card">
            <h3>Books</h3>
            <p>Manage Books</p>
        </a>

        <!-- Students -->
        <a href="admin/students.php" class="card">
            <h3>Students</h3>
            <p>Manage Students</p>
        </a>

        <!-- Issue Books -->
        <a href="admin/issued_books.php" class="card">
            <h3>Issue Books</h3>
            <p>Issue and Return Books</p>
        </a>

        <!-- Reports -->
        <a href="admin/reports.php" class="card">
            <h3>Reports</h3>
            <p>View Library Reports</p>
        </a>

    </div>

</div>

</body>
</html>
<?php
include '../config/db.php';

$sql = "SELECT * FROM students ORDER BY id DESC";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Students</title>

    <style>
        body {
            font-family: Arial;
            margin: 0;
            background: #f2f4f8;
        }

        header {
            background: #0879d1;
            color: white;
            padding: 25px;
        }

        header a {
            color: white;
            text-decoration: none;
        }

        .container {
            margin: 35px;
            background: white;
            padding: 25px;
            border-radius: 8px;
        }

        .btn {
            display: inline-block;
            background: #0879d1;
            color: white;
            padding: 12px 18px;
            text-decoration: none;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 13px;
            border: 1px solid #ddd;
            text-align: left;
        }

        th {
            background: #0879d1;
            color: white;
        }

        .success {
            color: green;
            margin-bottom: 15px;
        }
    </style>
</head>

<body>

<header>
    <h2>Manage Students</h2>
    <a href="dashboard.php">← Back to Dashboard</a>
</header>

<div class="container">

    <h2>Students Management</h2>

    <p>Welcome to the Students section of your library.</p>

    <a href="add_students.php" class="btn">+ Add Student</a>

    <?php if (isset($_GET['success'])) { ?>
        <p class="success">Student added successfully!</p>
    <?php } ?>

    <h3>Student Records</h3>

    <table>
        <tr>
            <th>ID</th>
            <th>Student ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Phone Number</th>
        </tr>

        <?php if ($result && mysqli_num_rows($result) > 0) { ?>

            <?php while ($row = mysqli_fetch_assoc($result)) { ?>

                <tr>
                    <td><?php echo htmlspecialchars($row['id']); ?></td>
                    <td><?php echo htmlspecialchars($row['student_id']); ?></td>
                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                    <td><?php echo htmlspecialchars($row['phone']); ?></td>
                </tr>

            <?php } ?>

        <?php } else { ?>

            <tr>
                <td colspan="5">No students found.</td>
            </tr>

        <?php } ?>

    </table>

</div>

</body>
</html>

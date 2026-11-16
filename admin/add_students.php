<?php
include '../config/db.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $student_id = mysqli_real_escape_string($conn, $_POST['student_id']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);

    $sql = "INSERT INTO students (student_id, name, email, phone)
            VALUES ('$student_id', '$name', '$email', '$phone')";

    if (mysqli_query($conn, $sql)) {
        header("Location: students.php?success=1");
        exit();
    } else {
        $message = "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Student</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f2f4f8;
            margin: 0;
            padding: 40px;
        }

        .container {
            background: white;
            max-width: 550px;
            margin: 30px auto;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 3px 12px rgba(0,0,0,0.1);
        }

        h2 {
            text-align: center;
            color: #222;
        }

        label {
            display: block;
            margin-top: 15px;
            font-weight: bold;
        }

        input {
            width: 100%;
            padding: 12px;
            margin-top: 7px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        button {
            width: 100%;
            padding: 13px;
            margin-top: 25px;
            background: #0879d1;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background: #005da8;
        }

        a {
            display: block;
            margin-top: 20px;
            text-align: center;
            color: #0879d1;
        }

        .error {
            color: red;
            text-align: center;
        }
    </style>
</head>

<body>

<div class="container">

    <h2>Add New Student</h2>

    <?php if ($message != "") { ?>
        <p class="error"><?php echo htmlspecialchars($message); ?></p>
    <?php } ?>

    <form method="POST" action="">

        <label>Student ID</label>
        <input type="text" name="student_id" required>

        <label>Student Name</label>
        <input type="text" name="name" required>

        <label>Email</label>
        <input type="email" name="email" required>

        <label>Phone Number</label>
        <input type="tel" name="phone" required>

        <button type="submit">Add Student</button>

    </form>

    <a href="students.php">Back to Students</a>

</div>

</body>
</html>
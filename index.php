<?php
session_start();

if (isset($_SESSION['admin_id'])) {
    header("Location: dashboard.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Login</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<div class="login-container">
    <div class="login-box">

        <div class="logo">📚</div>

        <h1>Welcome Back!</h1>
        <p>Library Management System</p>

        <form action="login.php" method="POST">

            <label>Username</label>
            <input type="text" name="username"
                   placeholder="Enter your username" required>

            <label>Password</label>
            <input type="password" name="password"
                   placeholder="Enter your password" required>

            <button type="submit">Login to Dashboard →</button>

        </form>

        <div class="footer">
            Your Knowledge Journey Starts Here
        </div>

    </div>
</div>

</body>
</html>
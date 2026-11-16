<?php
include("../config/db.php");

if (isset($_POST['add_book'])) {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $author = mysqli_real_escape_string($conn, $_POST['author']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $quantity = (int) $_POST['quantity'];

    $sql = "INSERT INTO books (title, author, category, quantity)
            VALUES ('$title', '$author', '$category', $quantity)";

    if (mysqli_query($conn, $sql)) {
        $message = "Book added successfully!";
    } else {
        $message = "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Books</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<h1>Books Management</h1>

<form method="POST">
    <input type="text" name="title" placeholder="Book Title" required>
    <input type="text" name="author" placeholder="Author" required>
    <input type="text" name="category" placeholder="Category" required>
    <input type="number" name="quantity" placeholder="Quantity" min="0" required>

    <button type="submit" name="add_book">Add Book</button>
</form>

<?php if (isset($message)) echo "<p>$message</p>"; ?>

<h2>Available Books</h2>

<table border="1" cellpadding="10">
<tr>
    <th>ID</th>
    <th>Title</th>
    <th>Author</th>
    <th>Category</th>
    <th>Quantity</th>
</tr>

<?php
$result = mysqli_query($conn, "SELECT * FROM books");

while ($row = mysqli_fetch_assoc($result)) {
    echo "<tr>";
    echo "<td>".$row['id']."</td>";
    echo "<td>".$row['title']."</td>";
    echo "<td>".$row['author']."</td>";
    echo "<td>".$row['category']."</td>";
    echo "<td>".$row['quantity']."</td>";
    echo "</tr>";
}
?>
</table>

</body>
</html>
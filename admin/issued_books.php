<?php
require_once "../config/db.php";

$message = "";
$error = "";

// ISSUE BOOK
if (isset($_POST['issue_book'])) {

    $student_id = (int)$_POST['student_id'];
    $book_id = (int)$_POST['book_id'];
    $due_date = $_POST['due_date'];

    try {
        $conn->begin_transaction();

        // Check available book quantity
        $stmt = $conn->prepare(
            "SELECT quantity FROM books WHERE id = ? FOR UPDATE"
        );
        $stmt->bind_param("i", $book_id);
        $stmt->execute();
        $book = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if (!$book || $book['quantity'] < 1) {
            throw new Exception("Book is not available!");
        }

        // Check student exists
        $stmt = $conn->prepare(
            "SELECT id FROM students WHERE id = ?"
        );
        $stmt->bind_param("i", $student_id);
        $stmt->execute();
        $student = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if (!$student) {
            throw new Exception("Student not found!");
        }

        // Insert issue record
        $stmt = $conn->prepare(
            "INSERT INTO issued_books
            (student_id, book_id, issue_date, due_date)
            VALUES (?, ?, CURDATE(), ?)"
        );

        $stmt->bind_param(
            "iis",
            $student_id,
            $book_id,
            $due_date
        );

        $stmt->execute();
        $stmt->close();

        // Reduce book quantity
        $stmt = $conn->prepare(
            "UPDATE books SET quantity = quantity - 1 WHERE id = ?"
        );
        $stmt->bind_param("i", $book_id);
        $stmt->execute();
        $stmt->close();

        $conn->commit();

        header("Location: issued_books.php?success=issued");
        exit();

    } catch (Exception $e) {
        $conn->rollback();
        $error = $e->getMessage();
    }
}


// RETURN BOOK
if (isset($_POST['return_book'])) {

    $issue_id = (int)$_POST['issue_id'];

    try {
        $conn->begin_transaction();

        // Find unreturned issue
        $stmt = $conn->prepare(
            "SELECT book_id FROM issued_books
             WHERE id = ? AND return_date IS NULL
             FOR UPDATE"
        );

        $stmt->bind_param("i", $issue_id);
        $stmt->execute();
        $record = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if (!$record) {
            throw new Exception("Book already returned or record not found!");
        }

        $book_id = (int)$record['book_id'];

        // Update return date
        $stmt = $conn->prepare(
            "UPDATE issued_books
             SET return_date = CURDATE()
             WHERE id = ?"
        );
        $stmt->bind_param("i", $issue_id);
        $stmt->execute();
        $stmt->close();

        // Increase book quantity
        $stmt = $conn->prepare(
            "UPDATE books SET quantity = quantity + 1 WHERE id = ?"
        );
        $stmt->bind_param("i", $book_id);
        $stmt->execute();
        $stmt->close();

        $conn->commit();

        header("Location: issued_books.php?success=returned");
        exit();

    } catch (Exception $e) {
        $conn->rollback();
        $error = $e->getMessage();
    }
}


// SUCCESS MESSAGES
if (isset($_GET['success'])) {
    if ($_GET['success'] == 'issued') {
        $message = "Book issued successfully!";
    } elseif ($_GET['success'] == 'returned') {
        $message = "Book returned successfully!";
    }
}

// GET STUDENTS
$students = $conn->query(
    "SELECT id, name, student_id FROM students ORDER BY name"
);

// GET AVAILABLE BOOKS
$books = $conn->query(
    "SELECT id, title, author, quantity
     FROM books
     WHERE quantity > 0
     ORDER BY title"
);

// GET ISSUED BOOK RECORDS
$records = $conn->query(
    "SELECT
        ib.id,
        s.name AS student_name,
        s.student_id AS student_code,
        b.title AS book_title,
        ib.issue_date,
        ib.due_date,
        ib.return_date
     FROM issued_books ib
     JOIN students s ON ib.student_id = s.id
     JOIN books b ON ib.book_id = b.id
     ORDER BY ib.id DESC"
);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Issue & Return Books</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: #f4f6f9;
            margin: 0;
        }

        header {
            background: #087bd3;
            color: white;
            padding: 22px 35px;
        }

        header h2 {
            margin: 0 0 10px;
        }

        header a {
            color: white;
            text-decoration: none;
        }

        .container {
            width: 94%;
            max-width: 1200px;
            margin: 30px auto;
        }

        .card {
            background: white;
            padding: 25px;
            margin-bottom: 25px;
            border-radius: 8px;
            box-shadow: 0 2px 8px #ddd;
        }

        input, select {
            width: 100%;
            padding: 12px;
            margin: 8px 0 18px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        button {
            background: #087bd3;
            color: white;
            padding: 12px 22px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background: #055da5;
        }

        .return-btn {
            background: #198754;
            padding: 8px 13px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th, td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: left;
        }

        th {
            background: #087bd3;
            color: white;
        }

        .success {
            background: #d4edda;
            color: #155724;
            padding: 12px;
            margin-bottom: 15px;
            border-radius: 5px;
        }

        .error {
            background: #f8d7da;
            color: #721c24;
            padding: 12px;
            margin-bottom: 15px;
            border-radius: 5px;
        }

        .status {
            font-weight: bold;
        }

        @media(max-width: 700px) {
            .card {
                overflow-x: auto;
            }

            table {
                min-width: 700px;
            }
        }
    </style>
</head>

<body>

<header>
    <h2>Issue & Return Books</h2>
    <a href="../dashboard.php">← Back to Dashboard</a>
</header>

<div class="container">

    <?php if ($message): ?>
        <div class="success">
            <?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>

    <?php if ($error): ?>
        <div class="error">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>


    <!-- ISSUE BOOK FORM -->

    <div class="card">

        <h2>Issue New Book</h2>

        <form method="POST">

            <label>Select Student</label>

            <select name="student_id" required>
                <option value="">-- Select Student --</option>

                <?php while ($student = $students->fetch_assoc()): ?>

                    <option value="<?= $student['id'] ?>">
                        <?= htmlspecialchars($student['name']) ?>
                        (<?= htmlspecialchars($student['student_id']) ?>)
                    </option>

                <?php endwhile; ?>

            </select>


            <label>Select Book</label>

            <select name="book_id" required>
                <option value="">-- Select Book --</option>

                <?php while ($book = $books->fetch_assoc()): ?>

                    <option value="<?= $book['id'] ?>">
                        <?= htmlspecialchars($book['title']) ?>
                        - <?= htmlspecialchars($book['author']) ?>
                        (Available: <?= $book['quantity'] ?>)
                    </option>

                <?php endwhile; ?>

            </select>


            <label>Due Date</label>

            <input
                type="date"
                name="due_date"
                min="<?= date('Y-m-d', strtotime('+1 day')) ?>"
                required
            >

            <button type="submit" name="issue_book">
                Issue Book
            </button>

        </form>

    </div>


    <!-- ISSUED BOOK RECORDS -->

    <div class="card">

        <h2>Issued Book Records</h2>

        <table>

            <tr>
                <th>ID</th>
                <th>Student</th>
                <th>Student ID</th>
                <th>Book Name</th>
                <th>Issue Date</th>
                <th>Due Date</th>
                <th>Return Date</th>
                <th>Action</th>
            </tr>

            <?php if ($records && $records->num_rows > 0): ?>

                <?php while ($row = $records->fetch_assoc()): ?>

                    <tr>
                        <td><?= $row['id'] ?></td>

                        <td>
                            <?= htmlspecialchars($row['student_name']) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($row['student_code']) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($row['book_title']) ?>
                        </td>

                        <td><?= htmlspecialchars($row['issue_date']) ?></td>

                        <td><?= htmlspecialchars($row['due_date']) ?></td>

                        <td>
                            <?= $row['return_date']
                                ? htmlspecialchars($row['return_date'])
                                : 'Not Returned' ?>
                        </td>

                        <td>

                            <?php if ($row['return_date'] === null): ?>

                                <form method="POST"
                                      onsubmit="return confirm('Confirm book return?');">

                                    <input type="hidden"
                                           name="issue_id"
                                           value="<?= $row['id'] ?>">

                                    <button
                                        type="submit"
                                        name="return_book"
                                        class="return-btn">
                                        Return
                                    </button>

                                </form>

                            <?php else: ?>

                                <span class="status">Returned</span>

                            <?php endif; ?>

                        </td>

                    </tr>

                <?php endwhile; ?>

            <?php else: ?>

                <tr>
                    <td colspan="8">No issued books found.</td>
                </tr>

            <?php endif; ?>

        </table>

    </div>

</div>

</body>
</html>
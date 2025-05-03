<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "mdb";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $subject = $_POST['subject'];
    $date = $_POST['date'];
    $branch = $_POST['branch'];
    $year = $_POST['year'];


    $stmt = $conn->prepare("INSERT INTO secexam (title, subject, date, branch, year) VALUES (?, ?, ?, ?, ?)");
    
    if ($stmt === false) {
        die("Error preparing statement: " . $conn->error);
    }

    $stmt->bind_param("sssss", $title, $subject, $date, $branch, $year);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo "<h2 style='color: #333;'>Submitted Data:</h2>";
        echo "<p><strong>Subject:</strong> $subject</p>";
        echo "<p><strong>Date:</strong> $date</p>";
        echo "<p style='color: green;'>Exam updated.</p>";
    } else {
        echo "<p style='color: red;'>Error storing data: " . $stmt->error . "</p>";
    }

    $stmt->close();
}

// Close the connection
$conn->close();
?>

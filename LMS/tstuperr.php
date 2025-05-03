<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mdb";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $ht = $_POST["ht"];
    $email = $_POST["email"];
    $ph = $_POST["ph"];
    $name = $_POST["name"];

    function sanitizeInput($input)
    {
        $sanitizedInput = $input;
        return $sanitizedInput;
    }

    function getStudentDetails($conn, $ht, $email, $ph, $name)
    {
        $ht = sanitizeInput($ht);
        $email = sanitizeInput($email);
        $ph = sanitizeInput($ph);
        $name = sanitizeInput($name);

        $sql = "SELECT * FROM students WHERE 1=1";
        if (!empty($ht)) {
            $sql .= " AND hallticket = '$ht'";
        }
        if (!empty($email)) {
            $sql .= " AND email = '$email'";
        }
        if (!empty($ph)) {
            $sql .= " AND phno = '$ph'";
        }
        if (!empty($name)) {
            $sql .= " AND fname LIKE '%$name%'";
        }

        $result = $conn->query($sql);

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    $studentDetails = getStudentDetails($conn, $ht, $email, $ph, $name);
}

$conn->close();
?>

<!DOCTYPE html>
<html>

<head>
    <title>Student Details</title>
    <style>
        body {
        font-family: Arial, sans-serif;
        background-color: #f2f2f2;
        margin: 0;
        padding: 0;
    }

    header {
        background-color: #333;
        color: #fff;
        padding: 20px;
        text-align: center;
    }

    .student-table {
        margin-top: 20px;
    }

    .student-row {
        display: flex;
        flex-direction: row;
        margin-bottom: 10px;
    }

    .student-row label {
        width: 150px;
        font-weight: bold;
    }

    .student-row span {
        margin-left: 10px;
    }

    .student-search {
        text-align: center;
        margin-top: 20px;
    }

    .student-search .student-row {
        justify-content: center;
    }

    .student-search input[type="text"] {
        padding: 5px;
        width: 200px;
    }

    .student-row input[type="submit"] {
        background-color: #4CAF50;
        color: #fff;
        padding: 8px 16px;
        border: none;
        cursor: pointer;
        font-size: 14px;
    }

    p {
        text-align: center;
        margin-top: 20px;
    }
    .previous-button {
           position: absolute;
           background-color: #4CAF50;
           color: white;
           padding: 10px 20px;
           border: none;
           border-radius: 4px;
           top: 10px;
           left: 10px;
           cursor: pointer;
           font-size: 16px;
        }

       
    </style>
</head>

<body>
    <header>
        <h1>Student Details</h1>
<a class="previous-button" href="tstudet.html">&lt; Previous</a>
    </header>
    <br>
    <center>
        <h2>Enter any one of the details in the given field</h2>
    </center>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <div class="student-search">
            <div class="student-row">
                <label>Hallticket:</label>
                <input type="text" name="ht">
            </div>
            <div class="student-row">
                <label>Email:</label>
                <input type="text" name="email">
            </div>
            <div class="student-row">
                <label>Phone:</label>
                <input type="text" name="ph">
            </div>
            <div class="student-row">
                <label>Name:</label>
                <input type="text" name="name">
            </div>
            </div>
             <div class="student-row">
                <center>
                 <input type="submit" value="Search">
                </center>
             </div>
        </div>
    </form>
    <?php if (!empty($studentDetails)) : ?>
        <div class="student-table">
            <?php foreach ($studentDetails as $student) : ?>
                <div class="student-row">
                    <label>Hallticket:</label>
                    <span><?php echo $student['hallticket']; ?></span>
                </div>
                <div class="student-row">
                    <label>Name:</label>
                    <span><?php echo $student['fname'] . " " . $student['lname']; ?></span>
                </div>
                <div class="student-row">
                    <label>Last Name:</label>
                    <span><?php echo $student['lname'] . " " . $student['lname']; ?></span>
                </div>
                <div class="student-row">
                    <label>Email:</label>
                    <span><?php echo $student['email']; ?></span>
                </div>
                <div class="student-row">
                    <label>Phone:</label>
                    <span><?php echo $student['phno']; ?></span>
                </div>
                <div class="student-row">
                    <label>Address:</label>
                    <span><?php echo $student['ad1']; ?></span>
                </div>
                <div class="student-row">
                    <label>Branch:</label>
                    <span><?php echo $student['br']; ?></span>
                </div>
                <div class="student-row">
                    <label>Year:</label>
                    <span><?php echo $student['yr']; ?></span>
                </div>
                <div class="student-row">
                    <label>Year:</label>
                    <span><?php echo $student['yr']; ?></span>
                </div>
                <div class="student-row">
                    <label>Date of Birth:</label>
                    <span><?php echo $student['dob']; ?></span>
                </div>
                <div class="student-row">
                    <label>Gender:</label>
                    <span><?php echo $student['gender']; ?></span>
                </div>
                <hr>
            <?php endforeach; ?>
        </div>
    <?php else : ?>
        <p>No student details found.</p>
    <?php endif; ?>
</body>

</html>

<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: signin.php');
    exit();
}

$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'root';
$DATABASE_PASS = '';
$DATABASE_NAME = 'thesis_distributed';

$con = new mysqli($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);

if ($con->connect_error) {
    die('Connection failed: ' . $con->connect_error);
}

if (isset($_GET['the_id'])) {
    $the_id = $_GET['the_id'];

    $sql = "SELECT the_topic, the_title, the_description FROM thesis WHERE the_id = ? AND the_supervising_prof_id = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("ii", $the_id, $_SESSION['user_id']);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($the_topic, $the_title, $the_description);
    $stmt->fetch();

    if ($stmt->num_rows > 0) {
        
        echo '<!DOCTYPE html>
        <html lang="el">
        <head>
            <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
            <meta charset="utf-8">
            <title>Edit Thesis</title>
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <link rel="stylesheet" href="welcome_nosingin.css">
          <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        </head>
        <body>
            <nav>
                <input type="checkbox" id="check">
                <label for="check" class="checkbtn">
                    <i class="fa fa-bars"></i>
                </label>
                <img class="image" src="images/logo_oldmoney.png" alt="thesis old money logo">
                <ul>
                <li><a href="teacher_showthesissubjects.php">Thesis Subjects</a></li>
                <li><a href="teacherassingthesis.php">Assign Thesis to student</a></li>
                <li><a href="teacher_thesisList_3.php">Thesis List</a></li>
                <li><a href="teacher_answers_requests.php">Requests</a></li>
                <li><a href="teacher_statistics.html">Statistics</a></li>
			    <li><a href="announcements.html">Announcements</a></li>
			    <li><a href="logout.php">Logout</a></li>
                </ul>
            </nav>
            <div class="seethroughbox1">
                <h2 class="form-title">Edit Thesis</h2>
                <form action="professor_updatethesis.php" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="the_id" value="' . $the_id . '">
                    <div class="main-user-info">
                        <div class="user-input-box">
                            <label for="the_topic">Topic</label>
                            <input type="text" id="the_topic"class="form-control" name="the_topic" value="' . htmlspecialchars($the_topic) . '">
                        </div>
                        <div class="user-input-box">
                            <label for="the_title">Τitle</label>
                            <input type="text" id="the_title" class="form-control" name="the_title" value="' . htmlspecialchars($the_title) . '">
                        </div>
                        <div class="user-input-box">
                            <label for="the_description">Description</label>
                            <textarea class="textareathesis" class="form-control" name="the_description" rows="10" cols="45">' . htmlspecialchars($the_description) . '</textarea>
                            </div>
                        <div class="user-input-box">
                            <label for="file">Choose a pdf file(optional):</label>
                            <input type="file" id="file" class="form-control" name="file" accept=".pdf">
                        </div>
                        <div class="form-submit-btn">
                            <input type="submit" value="Update">
                        </div>
                    </div>
                </form>
            </div>
        </body>
        </html>';
    } else {
        echo 'Thesis not found ';
    }

    $stmt->close();
}

$con->close();
?>

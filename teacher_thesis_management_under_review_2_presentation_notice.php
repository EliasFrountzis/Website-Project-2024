<?php
session_start();

// Retrieve the thesis ID from the URL
$thesis_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($thesis_id <= 0) {
    die(json_encode(['success' => false, 'message' => 'Invalid Thesis ID2.']));
}
?>

<!DOCTYPE html>
<html lang="el">
<head>
    <meta charset="UTF-8">
    <title>Thesis Presentation Notice Creation</title>
    <link rel="stylesheet" href="welcome_nosingin.css">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="teacher_thesis_management_under_review_2_presentation_notice_script.js"></script>
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

    <div class="seethroughbox">    
        <div id="grades-content">
            <h2 class="form-title">Thesis Presentation Notice Creation</h2>
            <br>
            <div id="textInputContainer">
                <textarea class="textareathesis" id="thesisText" placeholder="Type your thesis text here..."></textarea>
                <br>
                <div class="center-container">
                    <button type="submit" class="center-button" id="submitButton">Submit</button>
                </div>
            </div>
        </div>
    </div>

    
</body>
</html>

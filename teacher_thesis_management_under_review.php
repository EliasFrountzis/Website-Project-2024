<?php
session_start();

// Retrieve the thesis ID from the URL
$thesis_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($thesis_id <= 0) {
    die(json_encode(['success' => false, 'message' => 'Invalid Thesis ID.']));
}
?>

<!DOCTYPE html>
<html lang="el">
<head>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <meta charset="utf-8">
    <title>Menu</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="welcome_nosingin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <meta name="viewport" content="width=device-width,initial-scale=1.0"/>
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
        <h2 class="form-title">Thesis management</h2>
        <div class="flex-container"></div>
            <ul>
                <li>
                    <div class="create_subjects-box">
                        <a href="teacher_thesis_management_under_review_1_thesis_text.php?id=<?php echo $thesis_id; ?>">
                            <h2 class="form-title">Thesis Document</h2>
                        </a>
                    </div>
                </li>
                <div class="flex-container"></div>
                <li><div class="create_subjects-box"><a href="teacher_thesis_management_under_review_2_presentation_notice.php?id=<?php echo $thesis_id; ?>">
                    <h2 class="form-title">Thesis Presentation Notice</h2></a></div></li>
                <div class="flex-container"></div>
                <li>
                    <div class="create_subjects-box">
                        <a href="teacher_thesis_management_under_review_3_supervising_grade.php?id=<?php echo $thesis_id; ?>">
                            <h2 class="form-title">Grade Thesis as Supervisor</h2>
                        </a>
                    </div>
                </li>
                <div class="flex-container"></div>
                <li>
                    <div class="create_subjects-box">
                        <a href="teacher_thesis_management_under_review_4_co_grade.php?id=<?php echo $thesis_id; ?>">
                            <h2 class="form-title">Grade Thesis as Committee Member</h2>
                        </a>
                    </div>
                </li>
            </ul>
    </div>
    


</body>

</html>
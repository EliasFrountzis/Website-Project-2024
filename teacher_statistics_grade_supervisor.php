<!DOCTYPE html>
<html lang="el">
<head>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <meta charset="utf-8">
    <title>Average Finished Thesis Chart as Supervisor</title>
    <link rel="stylesheet" href="welcome_nosingin.css">
    <script src="teacher_statistics_grade_supervisor_script.js"></script>
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
        <h2 class="form-title">Average Grade Thesis Chart as Supervisor</h2>
        <canvas id="thesisChart"></canvas>
    </div>

    
</body>
</html>

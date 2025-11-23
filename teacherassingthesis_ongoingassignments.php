<?php
$con = mysqli_connect("localhost", "root", "", "thesis_distributed");

if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

// Fetch ongoing assignments for the professor
$query = "SELECT t.the_id, t.the_title, s.st_name, s.st_surname, s.st_id 
          FROM thesis t 
          JOIN student s ON t.the_st_id = s.st_id 
          WHERE t.the_status = 'under assignment'"; 
$query_run = mysqli_query($con, $query);
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

    <div class="seethroughbox2">
        <h2>Ongoing Assignments</h2>
        
        <?php
        if (mysqli_num_rows($query_run) > 0) {
            while ($assignment = mysqli_fetch_assoc($query_run)) {
                ?>
                <div class="show_subjects-box"> <!-- New div for each assignment -->
                    <h3><?php echo $assignment['the_title']; ?></h3>
                    <p>Assigned to: <?php echo $assignment['st_name'] . ' ' . $assignment['st_surname']; ?></p>
                    <p>Student ID: <?php echo $assignment['st_id']; ?></p>
                    <form action="teacherassingthesis_cancel_assignment.php" method="POST">
                        <input type="hidden" name="the_id" value="<?php echo $assignment['the_id']; ?>">
                        <button type="submit">Cancel Assignment</button>
                    </form>
                </div>
                <?php
            }
        } else {
            echo "<div>No ongoing assignments found.</div>";
        }
        ?>
    </div>
</body>
</html>

<?php
session_start();
$con = mysqli_connect("localhost", "root", "", "thesis_distributed");

if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === TRUE) {
    $user_id = $_SESSION['log_id'];

    // Check if the session variable is set
    if (isset($_SESSION['log_username'])) {
        $log_prof_email_id = trim($_SESSION['log_username']); // Trim whitespace
    } else {
        echo "Professor email not found in session.<br>";
        $log_prof_email_id = ''; // Set a default value or handle the error
    }

    // Get the professor's ID
    $prof_query = "SELECT prof_id FROM professor WHERE prof_email = ?";
    if ($stmt = $con->prepare($prof_query)) {
        $stmt->bind_param('s', $log_prof_email_id);
        $stmt->execute();
        $stmt->bind_result($prof_id);
        $stmt->fetch();
        $stmt->close();

        // Display the retrieved prof_id
        if (isset($prof_id)) {
            echo "Professor ID: " . htmlspecialchars($prof_id) . "<br>"; // Display the prof_id
        } else {
            echo "No professor found with that email.<br>";
        }
    } else {
        echo "Error preparing professor query: " . $con->error;
    }
} else {
    echo "You are not logged in.";
}
?>
<!DOCTYPE html>
<html lang="el">
<head>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="search_students.js"></script> <!-- Link to the new JavaScript file -->
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
            <li><a href="teacher_showthesissubjects.html">Thesis Subjects</a></li>
            <li><a href="teacherassingthesis.php">Assign Thesis to student</a></li>
            <li><a href="teacher_thesisList_3.php">Thesis List</a></li>
            <li><a href="teacher_answers_requests.php">Invites</a></li>
            <li><a href="teacher_statistics.html">Statistics</a></li>
            <li><a href="teacher_thesismanage.html">Thesis management</a></li>
        </ul>
    </nav>

    <div class="seethroughbox2">
        <h2 class="form-title">Expecto Studentum</h2>
        <?php
        if (isset($_GET['success'])) {
    echo "<div>Thesis assigned successfully!</div>";
}?>

<form action="" method="GET">
    <div class="main-user-info">
        <div class="user-input-box">
            <label for="searchstudent">Search Student</label>
            <input type="text"
                id="searchstudent"
                name="searchstudent"
                value="<?php if(isset($_GET['searchstudent'])){echo $_GET['searchstudent'];} ?>"
                placeholder="Magicly summon a student by searching their name"/>
            <button class="form-submit-btn">Search</button>
            <a href="teacherassingthesis_ongoingassignments.php" class="form-submit-btn">Ongoing Assignments</a> <!-- Updated button -->
        </div>
    </div>
</form>

        
    <?php
    if (isset($_GET['searchstudent'])) {
        $filtervalues = $_GET['searchstudent'];
        $query = "SELECT s.* FROM student s LEFT JOIN thesis t ON s.st_id = t.the_st_id WHERE CONCAT(s.st_name, s.st_surname, s.st_id) LIKE '%$filtervalues%' AND t.the_st_id IS NULL";
        $query_run = mysqli_query($con, $query);

        if (mysqli_num_rows($query_run) > 0) {
            foreach ($query_run as $items) {
                ?>
                <div class="show_subjects-box"> <!-- New div for each student's information -->
                    <h3><?=$items['st_surname']?></h3>
                    <p><?=$items['st_name']?></br></p>
                    <p><?=$items['st_id']?></br></p>

                    <form action="update_thesis.php" method="POST">
                        <label for="the_select">Select Thesis:</label>
                        <select name="the_id" id="the_select">
                            <?php
                            // Fetch thesis titles that do not have an assigned student ID
                            if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === TRUE) {
                                // ... rest of your code ...
                            
                                if (isset($prof_id)) {
                                    $thesis_query = "SELECT the_id, the_title FROM thesis WHERE the_supervising_prof_id = ?";
                                    if ($stmt = $con->prepare($thesis_query)) {
                                        $stmt->bind_param('i', $prof_id);
                                        $stmt->execute();
                                        $thesis_result = $stmt->get_result();
                            
                                        if ($thesis_result->num_rows > 0) {
                                            while ($thesis = $thesis_result->fetch_assoc()) {
                                                echo "<option value='{$thesis['the_id']}'>{$thesis['the_title']}</option>";
                                            }
                                        } else {
                                            echo "No theses found for this professor.<br>";
                                        }
                            
                                        $stmt->close();
                                    } else {
                                        echo "Error preparing thesis query: " . $con->error;
                                    }
                                } else {
                                    echo "Professor ID not found.";
                                }
                            } else {
                                echo "You are not logged in.";
                            }
                            ?>
                        </select>
                        <input type="hidden" name="student_id" value="<?=$items['st_id']?>">
                        <button type="submit">Assign Thesis</button>
                    </form>
                </div> <!-- End of student box -->
                <?php
            }
        } else {
            echo "<div>NO Record Found</div>";
        }
    }

    ?>

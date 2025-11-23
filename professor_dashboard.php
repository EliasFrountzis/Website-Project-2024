<?php session_start(); // έλεγχος αν ο χρήστης είναι συνδεμένος αλλιώς στο signin

if (!isset($_SESSION['loggedin'])) {
    header('Location: signin.html');
    exit();
} 

// έλεγχος αν το log tag είναι στο session
if (!isset($_SESSION['log_tag'])) {
    echo "Login tag not set.";
    exit();
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
            <div class="flex-container">
                <div class="flex-box">
                    <div class="flex-box-text">
                        <p>Welcome Professor, <?= htmlspecialchars($_SESSION['log_username']) ?>!</p>
                        <img class="image" src="images/professorpen.png" alt="profeesor professorpen">

                    </div>
                </div>
            </div>
    </body>

</html>










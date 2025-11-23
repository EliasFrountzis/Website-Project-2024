

<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
    header('Location: signin.html');
    exit();
}

//παίρνει το id από το session
$user_id = $_SESSION['user_id'];


$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'root';
$DATABASE_PASS = '';
$DATABASE_NAME = 'thesis_distributed';
$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);

//έλεγχος σύνδεσης
if (mysqli_connect_errno()) {
    exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}

//λήψη των προσωπικών πληροφοριών του
if ($stmt = $con->prepare('SELECT st_name, st_surname, st_number, st_ad_street, st_ad_number, st_ad_city, st_ad_postcode, st_father_name, st_landline, st_mobile, st_email FROM student WHERE st_id = ?')) {
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $stmt->bind_result($st_name, $st_surname, $st_number, $st_ad_street, $st_ad_number, $st_ad_city, $st_ad_postcode, $st_father_name, $st_landline, $st_mobile, $st_email);
    $stmt->fetch();
    $stmt->close();
} else {
    echo 'Failed to prepare the SQL statement.';
    exit();
}
?>


<!DOCTYPE html>
<html lang="el">
<head>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <meta charset="utf-8">
    <title>ProfileEdit</title>
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
            <li><a href="student_thesisshow.php">Thesis</a></li>
            <li><a href="student_profile.php">Profile</a></li>
            <li><a href="student_thesismanage.html">Thesis management</a></li>
            <li><a href="announcements.html">Announcements</a></li>
            <li><a href="logout.php">LOGOUT</a></li>
        </ul>
    </nav>
    <div class="seethroughbox1">
    <h4>Profile Edit Page</h4>
    <form method="post" action="studentprofileedit.php">
        <div class="main-user-info">
            <div class="user-input-box">
                <label for="fname">Street:</label><br>
                <input type="text" class="form-control" name="st_ad_street" placeholder="Street:" value="<?php echo htmlspecialchars($st_ad_street); ?>">
            </div>
            <div class="user-input-box">
                <label for="fname">Number:</label><br>
                <input type="text" class="form-control" name="st_ad_number" placeholder="Number:" value="<?php echo htmlspecialchars($st_ad_number); ?>">
            </div>
            <div class="user-input-box">
                <label for="fname">City:</label><br>
                <input type="text" class="form-control" name="st_ad_city" placeholder="City:" value="<?php echo htmlspecialchars($st_ad_city); ?>">
            </div>
            <div class="user-input-box">
                <label for="fname">Postcode:</label><br>
                <input type="text" class="form-control" name="st_ad_postcode" placeholder="Postcode:" value="<?php echo htmlspecialchars($st_ad_postcode); ?>">
            </div>
            <div class="user-input-box">
                <label for="fname">Email:</label><br>
                <input type="email" class="form-control" name="st_email" placeholder="Email:" value="<?php echo htmlspecialchars($st_email); ?>">
            </div>

    
            <div class="user-input-box">
                <label for="fname">Landline Telephone:</label><br>
                <input type="text" class="form-control" name="st_landline" placeholder="Landline Telephone:" value="<?php echo htmlspecialchars($st_landline); ?>">
            </div>
            <div class="user-input-box">
                <label for="fname">Mobile Telephone:</label><br>
                <input type="text" class="form-control" name="st_mobile" placeholder="Mobile Telephone:" value="<?php echo htmlspecialchars($st_mobile); ?>">
            </div>
            <input type="hidden" name="st_id" value="<?php echo htmlspecialchars($user_id); ?>">
            <div class="form-submit-btn"><input type="submit" name="edit" value="Submit"></div>
        </div>
    </form>
</body>
</html>

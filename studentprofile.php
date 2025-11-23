<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
    exit('Please log in first.');
}


$user_id = $_SESSION['user_id'];


$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'root';
$DATABASE_PASS = '';
$DATABASE_NAME = 'thesis_distributed';
$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);


if (mysqli_connect_errno()) {
    exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}

$sqlSelect = "SELECT * FROM student WHERE st_id='$user_id'";
$result = mysqli_query($con, $sqlSelect);


if ($result) {
    while ($data = mysqli_fetch_array($result)) {
        echo 'Name: ' . htmlspecialchars($data["st_name"]) . '<br>';
        echo 'Surname: ' . htmlspecialchars($data["st_surname"]) . '<br>';
        echo 'Student Number: ' . htmlspecialchars($data["st_number"]) . '<br>';
        echo 'Street: ' . htmlspecialchars($data["st_ad_street"]) . '<br>';
        echo 'Number: ' . htmlspecialchars($data["st_ad_number"]) . '<br>';
        echo 'City: ' . htmlspecialchars($data["st_ad_city"]) . '<br>';
        echo 'Postcode: ' . htmlspecialchars($data["st_ad_postcode"]) . '<br>';
        echo 'Father\'s Name: ' . htmlspecialchars($data["st_father_name"]) . '<br>';
        echo 'Landline Number: ' . htmlspecialchars($data["st_landline"]) . '<br>';
        echo 'Mobile Number: ' . htmlspecialchars($data["st_mobile"]) . '<br>';
        echo 'Email: ' . htmlspecialchars($data["st_email"]) . '<br>';
        echo '<a href="student_profileedit.php?id=' . htmlspecialchars($data["st_id"]) . '">Edit</a><br><br>';
    }
} else {
    echo "Error: " . mysqli_error($con);
}
?>

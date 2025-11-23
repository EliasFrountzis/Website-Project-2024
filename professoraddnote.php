<?php
session_start();

if (!isset($_SESSION['loggedin'])) {
    exit('Please log in first.');
}

$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'root';
$DATABASE_PASS = '';
$DATABASE_NAME = 'thesis_distributed';
$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);

if (mysqli_connect_errno()) {
    die('Failed to connect to MySQL: ' . mysqli_connect_error());
}

$user_id = $_SESSION['user_id'];

if (isset($_POST['note_text']) && isset($_POST['thesis_id'])) {
    $note_text = $_POST['note_text'];
    $thesis_id = intval($_POST['thesis_id']);

    $query = "INSERT INTO notes (note_prof_id, note_text, note_the_id) VALUES (?, ?, ?)";
    $stmt = $con->prepare($query);
    if (!$stmt) {
        die('Prepare failed: ' . $con->error);
    }
    $stmt->bind_param("isi", $user_id, $note_text, $thesis_id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo 'Note added successfully!';
    } else {
        echo 'Failed to add note.';
    }
}

mysqli_close($con);
?>

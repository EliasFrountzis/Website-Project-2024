<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
    header('Location: signin.html');
    exit();
}

$user_id = $_SESSION['user_id'];

if (isset($_POST['st_ad_street'], $_POST['st_ad_number'], $_POST['st_ad_city'], $_POST['st_ad_postcode'], $_POST['st_email'], $_POST['st_landline'], $_POST['st_mobile'])) {
    $DATABASE_HOST = 'localhost';
    $DATABASE_USER = 'root';
    $DATABASE_PASS = '';
    $DATABASE_NAME = 'thesis_distributed';
    $con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);

    if (mysqli_connect_errno()) {
        exit('Failed to connect to MySQL: ' . mysqli_connect_error());
    }

    // έναρξη συναλλαγής
    mysqli_begin_transaction($con);

    try {
        //απενεργοποίηση foreign key checks
        mysqli_query($con, "SET foreign_key_checks = 0");

        if ($stmt = $con->prepare('SELECT st_email FROM student WHERE st_id = ?')) {
            $stmt->bind_param('i', $user_id);
            $stmt->execute();
            $stmt->bind_result($old_email);
            $stmt->fetch();
            $stmt->close();
        } else {
            throw new Exception('Failed to retrieve previous email.');
        }

        if ($stmt = $con->prepare('UPDATE student SET st_ad_street = ?, st_ad_number = ?, st_ad_city = ?, st_ad_postcode = ?, st_email = ?, st_landline = ?, st_mobile = ? WHERE st_id = ?')) {
            $stmt->bind_param('sssssssi', $_POST['st_ad_street'], $_POST['st_ad_number'], $_POST['st_ad_city'], $_POST['st_ad_postcode'], $_POST['st_email'], $_POST['st_landline'], $_POST['st_mobile'], $user_id);
            if (!$stmt->execute()) {
                throw new Exception('Error updating student table: ' . $stmt->error);
            }
            $stmt->close();
        } else {
            throw new Exception('Failed to prepare the SQL statement for student table.');
        }

        
        if ($stmt = $con->prepare('UPDATE request SET req_st_email_id = ? WHERE req_st_email_id = ?')) {
            $stmt->bind_param('ss', $_POST['st_email'], $old_email);
            if (!$stmt->execute()) {
                throw new Exception('Error updating request table: ' . $stmt->error);
            }
            $stmt->close();
        } else {
            throw new Exception('Failed to prepare the SQL statement for request table.');
        }

        
        if ($stmt = $con->prepare('UPDATE login SET log_username = ?, log_st_email_id = ? WHERE log_st_email_id = ?')) {
            $stmt->bind_param('sss', $_POST['st_email'], $_POST['st_email'], $old_email);
            if (!$stmt->execute()) {
                throw new Exception('Error updating login table: ' . $stmt->error);
            }
            $stmt->close();
        } else {
            throw new Exception('Failed to prepare the SQL statement for login table.');
        }

        // ενεργοποίηση foreign key checks
        mysqli_query($con, "SET foreign_key_checks = 1");

        mysqli_commit($con);
        header('Location: student_profile.php');
        exit('Profile updated successfully!');
    } catch (Exception $e) {
        mysqli_rollback($con);
        exit('Transaction failed: ' . $e->getMessage());
    }

    mysqli_close($con);
} else {
    exit('Processing failed. Please fill in all required fields.');
}


?>

<?php
$mysqli = new mysqli("localhost", "root", "", "thesis_distributed");

// Check connection
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Fetch all users
$result = $mysqli->query("SELECT log_id, log_password FROM login");

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $log_id = $row['log_id'];
        $plain_password = $row['log_password']; // This is the plain text password

        // Check if the password is already hashed
        if (!password_get_info($plain_password)['algo']) {
            // Hash the password
            $hashed_password = password_hash($plain_password, PASSWORD_DEFAULT);

            // Update the database with the hashed password
            $update_stmt = $mysqli->prepare("UPDATE login SET log_password = ? WHERE log_id = ?");
            $update_stmt->bind_param("si", $hashed_password, $log_id);
            $update_stmt->execute();
            $update_stmt->close();
        } else {
            echo "Password for log_id $log_id is already hashed.<br>";
        }
    }

    $result->close();
} else {
    echo "Error fetching users: " . $mysqli->error;
}

$mysqli->close();
?>
<?php
session_start();
//συνάρτηση για δημιουργία τυχαίων κωδικών
function generatePassword($length = 8) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters); 
    $randomPassword = ''; 
    for ($i = 0; $i < $length; $i++) {
        $randomPassword .= $characters[rand(0, $charactersLength - 1)]; // κάθε φορά επιλέγεται τυχαία ένας χαρακτήρας με το rand δημιουργείται ένα τυχαίο ευρετήριο για αυτό
    }
    return $randomPassword;
}



try {
    $conn = new PDO("mysql:host=localhost;dbname=thesis_distributed", "root", ""); // pdo σύνδεση με τη βάση
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $conn->prepare("SELECT st_email FROM student WHERE passexecuted = FALSE");
    $stmt->execute();
    $students = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($students)) {
        $_SESSION['studentMessage'] = 'No students found.';
    } else {
        $_SESSION['studentMessage'] = 'Students found: ' . count($students);
        foreach ($students as $student) {
            $log_password = generatePassword();
            $hashedPassword = password_hash($log_password, PASSWORD_DEFAULT);

            $insertStmt = $conn->prepare("INSERT INTO login (log_username, log_password, log_tag) VALUES (?, ?, ?)");
            $insertStmt->execute([$student['st_email'], $hashedPassword, 'student']);

            $updateStmt = $conn->prepare("UPDATE student SET passexecuted = TRUE WHERE st_email = ?");
            $updateStmt->execute([$student['st_email']]);

            $passwordEntry = 'Email: ' . $student['st_email'] . ' - Password: ' . $log_password . PHP_EOL;
            $_SESSION['studentPasswords'][] = $passwordEntry;
        }
    }

} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

try {
    $stmt = $conn->prepare("SELECT prof_email FROM professor WHERE passexecuted = FALSE");
    $stmt->execute();
    $professors = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($professors)) {
        $_SESSION['professorMessage'] = 'No professors found.';
    } else {
        $_SESSION['professorMessage'] = 'Professors found: ' . count($professors);
        foreach ($professors as $professor) {
            $log_password = generatePassword();
            $hashedPassword = password_hash($log_password, PASSWORD_DEFAULT);

            $insertStmt = $conn->prepare("INSERT INTO login (log_username, log_password, log_tag) VALUES (?, ?, ?)");
            $insertStmt->execute([$professor['prof_email'], $hashedPassword, 'professor']);

            $updateStmt = $conn->prepare("UPDATE professor SET passexecuted = TRUE WHERE prof_email = ?");
            $updateStmt->execute([$professor['prof_email']]);

            $passwordEntry = 'Email: ' . $professor['prof_email'] . ' - Password: ' . $log_password . PHP_EOL;
            $_SESSION['professorPasswords'][] = $passwordEntry;
        }
    }

} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

// Ανακατεύθυνση 
header('Location: secretary_creatingaccounts.php');
exit();
?>

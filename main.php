<?php
session_start();
// έλεγχος συνδεσης , αν δεν είναι συνδεδεμένος ανακατεύθυνη στο signin
if (!isset($_SESSION['loggedin'])) {
	header('Location: signin.html');
	exit;
}

if (!isset($_SESSION['log_tag'])) {
	 echo "Login tag not set."; 
	 exit; 
	}

$log_tag = $_SESSION['log_tag'];
$user_id = $_SESSION['user_id'];

//έλεγχος ιδιότητας χρήστη ώστε ανάλογα με το αν είναι φοιτητής καθηγητής ή η γραμματεία να οδηγηθεί στην ανάλογη σελίδα
switch ($log_tag) {
    case 'Student':
        header("Location: student_dashboard.php");
        break;
    case 'Professor':
        header("Location: professor_dashboard.php");
        break;
    case 'Admin':
        header("Location: admin_dashboard.php");
        break;
    default:
        echo "Invalid login tag.";
        break;
}


?>


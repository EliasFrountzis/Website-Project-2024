<?php
session_start();

$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'root';
$DATABASE_PASS = '';
$DATABASE_NAME = 'thesis_distributed';

// σύνδεση με βάση
$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);

// εντοπισμος λάθους συνδεσης με βάση
if (mysqli_connect_errno()) {
    exit('Αδυναμία σύνδεσης με τη βάση: ' . mysqli_connect_error());
}

//έλεγχος για ελλειπή στοιχεία 
if (!isset($_POST['log_username'], $_POST['log_password'])) {
    exit('Add your email and password !');
}

if ($stmt = $con->prepare('SELECT log_id, log_password, log_tag FROM login WHERE log_username = ?')) {
    $stmt->bind_param('s', $_POST['log_username']); //πέρασμα παραμέτρου στο stmt
    $stmt->execute();
    $stmt->store_result();

    //έλεγχος αν επιστράφηκαν αποτελέσματα και συνδεση τους με τις μεταβλητές
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($log_id, $log_password, $log_tag);
        $stmt->fetch();

        //ελεγχος κωδικό
        if (password_verify($_POST['log_password'], $log_password)) {
            session_regenerate_id(); // αναγεννά το session id δημιουργώντας καινούργιο για την αποφυγή επιθέσεων από εισβολέα στη σύνδεση
            $_SESSION['loggedin'] = TRUE;
            $_SESSION['log_username'] = $_POST['log_username'];
            $_SESSION['log_id'] = $log_id;
            $_SESSION['log_tag'] = $log_tag;
//έλεγχος για την ιδιότητα του χρήστη αν είναι καθηγητής η φοιτητής και πέρασμα του id του στο user id
            if ($log_tag == 'Student') {
                if ($stmt = $con->prepare('SELECT st_id FROM student WHERE st_email = ?')) {
                    $stmt->bind_param('s', $_POST['log_username']);
                    $stmt->execute();
                    $stmt->bind_result($user_id);
                    $stmt->fetch();
                    $_SESSION['user_id'] = $user_id;
                }
            } elseif ($log_tag == 'Professor') {
                if ($stmt = $con->prepare('SELECT prof_id FROM professor WHERE prof_email = ?')) {
                    $stmt->bind_param('s', $_POST['log_username']);
                    $stmt->execute();
                    $stmt->bind_result($user_id);
                    $stmt->fetch();
                    $_SESSION['user_id'] = $user_id;
                }
            }

            header('Location: main.php');
        } else {
            $error_message = 'Wrong password!';
        }
    } else {
        $error_message = 'Wrong username!';
    }

    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="el">
<head>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <meta charset="utf-8">
    <title>SignIn</title>
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
            <li><a href="announcements.html">Ανακοινώσεις</a></li>
            <li><a href="signin.html">Sign in</a></li>
        </ul>
    </nav>

  
<div class="seethroughbox2">
    <?php
    if (isset($error_message)) {
        echo $error_message;
    }
    ?>
</div>
</body>
</html>


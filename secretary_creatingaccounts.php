<!DOCTYPE html>
<html lang="el">

<head>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <meta charset="utf-8">
    <title>Accounts</title>
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
            <li><a href="secretary_showthesis.php">Thesis</a></li>
            <li><a href="secretary_addjson.php">JSON</a></li>
            <li><a href="secretary_creatingaccounts.php">Creating accounts</a></li>
            <li><a href="admin_thesismanage.html">Thesis management</a></li>
            <li><a href="announcements.html">Announcements</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>


    <div class="seethroughbox2">
    
    <h1>Creating login accounts</h1>
    <form action="generatepassword.php" method="post">
        <div class="user-input-box">
        <div class="form-submit-btn"><input type="submit" value="Execute"></div>
      
    </form>
    </div>


    <?php
    session_start();

    if (isset($_SESSION['studentMessage'])) {
        echo '<p>' . $_SESSION['studentMessage'] . '</p>';
        unset($_SESSION['studentMessage']);
    }

    if (isset($_SESSION['studentPasswords'])) {
        foreach ($_SESSION['studentPasswords'] as $password) {
            echo '<p>' . $password . '</p>';
        }
        unset($_SESSION['studentPasswords']);
    }

    if (isset($_SESSION['professorMessage'])) {
        echo '<p>' . $_SESSION['professorMessage'] . '</p>';
        unset($_SESSION['professorMessage']);
    }

    if (isset($_SESSION['professorPasswords'])) {
        foreach ($_SESSION['professorPasswords'] as $password) {
            echo '<p>' . $password . '</p>';
        }
        unset($_SESSION['professorPasswords']);
    }
    ?>
</body>
</html>

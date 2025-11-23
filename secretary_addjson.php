<!DOCTYPE html>
<html lang="el">

<head>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <meta charset="utf-8">
    <title>Add JSON</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="welcome_nosingin.css">
</head>


<nav>
    <input type="checkbox" id="check">
    <label for="check" class="checkbtn">
        <i class="fa fa-bars"></i>
    </label>
    <img class="image" src="images/logo_oldmoney.png" alt="thesis old money logo">

    <ul>
            <li><a href="secretary_showthesis.php">Thesis </a></li>
            <li><a href="secretary_addjson.php">JSON</a></li>
            <li><a href="secretary_creatingaccounts.php">Creating accounts</a></li>
            <li><a href="admin_thesismanage.html">Thesis management</a></li>
            <li><a href="announcements.html">Announcements</a></li>
            <li><a href="logout.php">Logout</a></li>
    </ul>
</nav>

<body>
    <div class="seethroughbox2">
    
    <h1>Upload JSON File</h1>
    <form action="secretaryaddjson.php" method="post" enctype="multipart/form-data">
        <div class="user-input-box">
        <label for="file">Choose JSON file:</label>
        <input type="file" name="file" id="file" accept=".json" required> </div>
        <div class="form-submit-btn"><input type="submit" value="Upload"></div>
    </form>
    

    <?php if (isset($_GET['message'])) {
         if ($_GET['message'] == '1') 
         { echo '<p>Data successfully inserted into the database.</p>'; } 
         elseif ($_GET['message'] == '2')
          { echo '<p>Please upload a valid JSON file.</p>'; } 
         elseif ($_GET['message'] == '3')
     { echo '<p>There was an error uploading the file.</p>'; } } ?>
    </div>
</body>
</html>




<!DOCTYPE html>
<html lang="el">

<head>
<head>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <meta charset="utf-8">
    <title>Profile</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="welcome_nosingin.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() { //αυτόματα φορτώνονται τα δεδομένα όταν φορτωθεί η σελίδα
            $.ajax({
                url: 'studentprofile.php',
                type: 'GET',
                success: function(response) {
                    console.log('Response:', response); //φόρτωση απάντησης
                    $('#dataContainer').html(response);
                },
                error: function(xhr, status, error) {
                    console.error('Error: ' + error);
                }
            });
        });
    </script>
</head>

<body>
    <nav> <input type="checkbox" id="check"> <label for="check" class="checkbtn"> <i class="fa fa-bars"></i> </label> <img class="image" src="images/logo_oldmoney.png" alt="thesis old money logo">
        <ul>
            <li><a href="student_thesisshow.php">Thesis</a></li>
            <li><a href="student_profile.php">Profile</a></li>
            <li><a href="student_thesismanage.html">Thesis management</a></li>
            <li><a href="announcements.html">Announcements</a></li>
            <li><a href="logout.php">LOGOUT</a></li>
        </ul>
    </nav>
    <div>
        <div class="seethroughbox2">
            <header>
                <h2>Personal Information</h2>
            </header>
            <div id="dataContainer">  </div>
        </div>
    </div>
</body>

</html>


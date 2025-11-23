
<!DOCTYPE html>
<html lang="el">
<head>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <meta charset="utf-8">
    <title>Theses Management</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="welcome_nosingin.css">
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

    <?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "thesis_distributed";

$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$thesis_id = intval($_GET['id']); // τσεκ integer 



$sql = "SELECT t.the_st_id, t.the_grade, s.st_thesis_url
        FROM thesis t
        LEFT JOIN student_thesis_urls s ON t.the_st_id = s.st_url_st_id
        WHERE t.the_id = $thesis_id";

$result = $conn->query($sql);

if ($result === false) {
    echo "Error in query: " . $conn->error;
} else {
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $st_id = $row['the_st_id'];
        $the_grade = $row['the_grade'];
        $thesis_url = $row['st_thesis_url'];

        echo '<div class="seethroughbox2">';
        
        if ($the_grade !== null && $thesis_url !== null) {
            echo "<button id='finishButton' >Mark as Finished</button>";
        } elseif ($the_grade === null) {
            echo "<p>Missing the grade.</p>";
        } elseif ($thesis_url === null) {
            echo "<p>Missing URL.</p>";
        }
        
        echo '</div>';
    } else {
        echo "<div class='seethroughbox2'><p>No thesis found with the given ID.</p></div>";
    }
}

$conn->close();
?>

        <script>
            $(document).ready(function() {
                $('#finishButton').click(function() {
                    $.ajax({
                        url: 'secretary_urupdatethesis.php',
                        type: 'POST',
                        data: { thesis_id: <?php echo $thesis_id; ?> },
                        success: function(response) {
                            alert(response);
                            window.location.href = 'admin_thesismanage.html'; // επαναφόρτωση του thesis management
                        },
                        error: function() {
                            alert('Error updating thesis status.');
                        }
                    });
                });
            });
        </script>
    </div>
</body>
</html>

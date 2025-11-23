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

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['fetch'])) {
    $query = "
        SELECT the_id, the_title 
        FROM thesis 
        WHERE the_supervising_prof_id = ? AND the_status = 'ongoing'
    ";
    $stmt = $con->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $theses = [];
    while ($row = $result->fetch_assoc()) {
        $theses[] = $row;
    }

    mysqli_close($con);

    header('Content-Type: application/json');
    echo json_encode($theses);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['thesis_id'])) {
        $thesis_id = $_POST['thesis_id'];

        $query = "UPDATE thesis SET the_status = 'under review' WHERE the_id = ?";
        $stmt = $con->prepare($query);
        $stmt->bind_param("i", $thesis_id);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            echo 'Thesis status updated successfully!';
        } else {
            echo 'Failed to update thesis status.';
        }
    }

    mysqli_close($con);
    exit;
}
?>
<!DOCTYPE html>
<html lang="el">
<head>
    <meta charset="utf-8">
    <title>Supervisor Thesis</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="welcome_nosingin.css">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
</head>
<body>
    <nav>
        <input type="checkbox" id="check">
        <label for="check" class="checkbtn">
            <i class="fa fa-bars"></i>
        </label>
        <img class="image" src="images/logo_oldmoney.png" alt="thesis old money logo">
        <ul>
            <li><a href="teacher_showthesissubjects.php">Thesis Subjects</a></li>
            <li><a href="teacherassingthesis.php">Assign Thesis to student</a></li>
            <li><a href="teacher_thesisList_3.php">Thesis List</a></li>
            <li><a href="teacher_answers_requests.php">Requests</a></li>
            <li><a href="teacher_statistics.html">Statistics</a></li>
			<li><a href="announcements.html">Announcements</a></li>
			<li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>

    <div class="seethroughbox" id="thesis-container">
        <!-- οι λεπτομέρειες θα φορτωθούν εδώ -->
    </div>

    <div id="message" class="seethroughbox" style="display: none;">
        <!-- το μνμ θα φορτωθει εδώ -->
    </div>

    <script>
        $(document).ready(function() {
            fetchTheses();

            function fetchTheses() {
                $.ajax({
                    url: 'professor_thesisupdatestatus.php?fetch=true',
                    method: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        var thesisList = '<h1>Thesis Titles (Ongoing)</h1><ul>';
                        $.each(data, function(index, thesis) {
                            thesisList += '<li><a href="#" class="thesis-link" data-id="' + thesis.the_id + '">' + thesis.the_title + '</a></li>';
                        });
                        thesisList += '</ul>';
                        $('#thesis-container').html(thesisList);
                    },
                    error: function(xhr, status, error) {
                        $('#message').text('Error fetching theses: ' + error).show();
                    }
                });
            }

            $(document).on('click', '.thesis-link', function(e) {
                e.preventDefault();
                var thesisId = $(this).data('id');
                $.ajax({
                    url: 'professor_thesisupdatestatus.php',
                    method: 'POST',
                    data: { thesis_id: thesisId },
                    success: function(response) {
                        $('#message').text('Thesis status updated to "under review"').show();
                        fetchTheses(); // επαναφόρτωση των θεματων για να φανούν οι αλλαγές
                    },
                    error: function(xhr, status, error) {
                        $('#message').text('Error updating thesis status: ' + error).show();
                    }
                });
            });
        });
    </script>
</body>
</html>



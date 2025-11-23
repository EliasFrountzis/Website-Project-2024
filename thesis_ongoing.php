<?php session_start();
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
$user_email = $_SESSION['log_username']; 
 $thesis_id = isset($_GET['id']) ? intval($_GET['id']) : 0; // πέρασμα τίτλου και id
 $query = "SELECT t.the_title, p.prof_email FROM thesis t JOIN professor p ON t.the_supervising_prof_id = p.prof_id WHERE t.the_id = ?"; $stmt = $con->prepare($query); $stmt->bind_param('i', $thesis_id); $stmt->execute(); $stmt->bind_result($thesis_title, $supervising_prof_email); $stmt->fetch(); $stmt->close(); // ελέγχει αν ο καθηγητης είναι ο supervisor 
$is_supervisor = ($user_email == $supervising_prof_email);
mysqli_close($con); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Thesis Ongoing</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="welcome_nosingin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
</head>

<body>
    <nav>
        <input type="checkbox" id="check">
        <label for="check" class="checkbtn"><i class="fa fa-bars"></i></label>
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
    <div class="seethroughbox1">
        <h4><?php echo htmlspecialchars($thesis_title); ?></h4>
        <div class="form-submit-btn">
            <button onclick="showNoteForm()">Add Note</button>
            <?php if ($is_supervisor): ?>
                <button onclick="cancelThesis()">Cancel Thesis</button>
                <button onclick="updateStatus()">Update Status</button>
            <?php endif; ?>
        </div>
        <div class="seethroughbox2">
            <h1>Previous Notes:</h1>
            <table id="notesTable">
                <thead>
                    <tr>
    
                    </tr>
                </thead>
                <tbody id="notesList"></tbody>
            </table>
        </div>
        <div id="noteForm" style="display: none;">
            <h4>Add a New Note:</h4>
            <form id="addNoteForm">
                <input type="hidden" name="thesis_id" value="<?php echo $thesis_id; ?>">
                <textarea name="note_text" rows="4" cols="50" required></textarea><br>
                <div class="form-submit-btn"><input type="submit" value="Save Note"></div>
            </form>
        </div>
    </div>
    <script>
        function fetchNotes() {
            $.ajax({
                url: 'professoroldnote.php',
                type: 'GET',
                data: {
                    thesis_id: <?php echo $thesis_id; ?>
                },
                success: function(data) {
                    var notes = JSON.parse(data);
                    var notesList = $('#notesList');
                    notesList.empty();
                    notes.forEach(function(note) {
                        notesList.append('<tr><td>' + note.note_text + '</td></tr>');
                    });
                }
            });
        }

        function showNoteForm() {
            document.getElementById('noteForm').style.display = 'block';
        }

        function cancelThesis() {
            $.ajax({
                url: 'professorcancelthesis.php',
                type: 'POST',
                data: {
                    'thesis-title': '<?php echo $thesis_title; ?>'
                },
                success: function(response) {
                   
                        var data = JSON.parse(response);
                        if (data.status === 'success') {
                            alert('Thesis cancelled successfully!');
                            window.location.reload();
                        } else {
                            alert('Failed to cancel thesis: ' + data.error);
                        }
                    } 
                ,
                error: function(xhr, status, error) {
                    alert('Error cancelling thesis: ' + error);
                }
            });
        }

        function updateStatus() {
            $.ajax({
                url: 'professorthesisupdatestatus.php',
                type: 'POST',
                data: {
                    thesis_id: <?php echo $thesis_id; ?>
                },
                success: function(response) {
                    alert(response);
                    fetchNotes(); // επαναφόρτωση note για να φανουν οι καινούργιες
                },
                error: function(xhr, status, error) {
                    alert('Error updating thesis status: ' + error);
                }
            });
        }
        $(document).ready(function() {
            fetchNotes();
            $('#addNoteForm').on('submit', function(e) {
                e.preventDefault();
                $.ajax({
                    url: 'professoraddnote.php',
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        alert(response);
                        fetchNotes();
                        $('#addNoteForm')[0].reset();
                        document.getElementById('noteForm').style.display = 'none';
                    }
                });
            });
        });
    </script>
</body>

</html>

<!DOCTYPE html>
<html lang="el">
<head>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <meta charset="utf-8">
    <title>Theses</title>
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
            <li><a href="secretary_showthesis.php">Thesis </a></li>
            <li><a href="secretary_addjson.php">JSON</a></li>
            <li><a href="secretary_creatingaccounts.php">Creating accounts</a></li>
            <li><a href="admin_thesismanage.html">Thesis management</a></li>
            <li><a href="announcements.html">Announcements</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>

 <div class="seethroughbox2"><div id="thesis-container">
       <!-- λεπτομέρειες των διπλωματικών θα φορτωθούν εδώ -->

    </div>

    <script>
       $(document).ready(function() {
    //κατα την φόρτωση θα ληφθούν οι τιτλοι 
    fetchThesisTitles();

    // συνάρτηση για την φόρτωση των διπλωματικών
    function fetchThesisTitles() {
        $.ajax({
            url: 'secretaryshowthesis.php', //αποστολή του αιτήματος στο url
            method: 'GET', //μέθοδος HTTP που θα χρησ για το αίτημα
            success: function(data) { //συνάρτηση εφόσον είναι επιτυχημένο το αίτημα
                $('#thesis-container').html(data); //εισαγωγή των δεδομένων στο html στοιχείο με id thesis-container 
            },
            error: function(xhr, status, error) {
                console.error('Error fetching thesis titles:', error); // Debugging statement
            }
        });
    }

    // συνάρτηση για την λήψη των λεπτομερείων της διπλωμ
    function fetchThesisDetails(id) {
        $.ajax({
            url: 'secretaryshowthesis.php',
            method: 'GET',
            data: { id: id }, // στέλνετε το id της διπλωματικής μαζί με το αίτημα
            success: function(data) {
                $('#thesis-container').html(data);
            },
            error: function(xhr, status, error) {
                console.error('Error fetching thesis details:', error); // για το debug
            }
        });
    }

    // event για τα click στα λινκ των διπλωματικών 
    $(document).on('click', '.thesis-link', function(e) {
        e.preventDefault(); //για την ανάκτηση και την εμφάνιση των λεπτομέρειων χωρίς φόρτωση ξανά τη σελίδας
        var thesisId = $(this).data('id'); // πέρασμα id απο την διπλωματική που επιλέχθηκε
        fetchThesisDetails(thesisId);
    });
});

    </script>
</body>
</html>

<?php
// σύνδεση με τη βάση
$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'root';
$DATABASE_PASS = '';
$DATABASE_NAME = 'thesis_distributed';
$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);

// έλεγχος συνδεσης
if (mysqli_connect_errno()) {
    die('Failed to connect to MySQL: ' . mysqli_connect_error());
}

// έλεγχος αν το id έχει περαστεί με το αίτημα
if (isset($_GET['id'])) {
    $thesis_id = $_GET['id'];

    // query για την εύρεση των λεπτομερειών της διπλωματικής
    $query2 = "SELECT t.the_topic, t.the_title, t.the_description, t.the_status, p.prof_name, p.prof_surname, c.co_prof_1_id, c.co_prof_2_id, t.the_date_assignation 
               FROM thesis t 
               JOIN professor p ON t.the_supervising_prof_id = p.prof_id 
               JOIN committee c ON t.the_co_id = c.co_id 
               WHERE t.the_id = ?";
    $stmt = $con->prepare($query2);
    $stmt->bind_param("i", $thesis_id);//πέρασμα id diplom 
    $stmt->execute();
    $result = $stmt->get_result(); // λήψη των αποτελεσμάτων από το query 

    //έλεγχος αν επιστράφηκαν αποτελέσματα
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc(); 
        $date_assignation = $row['the_date_assignation']; //λήψη της ημερομηνίας ανάθεσης από την γραμμή του πίνακα των αποτελεσμάτων
        $date_assignation_obj = new DateTime($date_assignation); //ημερομηνία ανάθεση
        $current_date_obj = new DateTime(); // αντικείμενο για την τωρινή ημερομηνία
        $interval = $current_date_obj->diff($date_assignation_obj); //αφαίρεση των ημερομηνίων για υπολογισμό του χρόνου που έχει μεσολαβήσει
        $time_since_assignment = $interval->y . ' years, ' . $interval->m . ' months, ' . $interval->d . ' days'; 
       
        

        echo "<h1>Thesis Details</h1>"; 
        echo "<p class='bold-text'>Topic:</p><p>" . $row['the_topic'] . "</p>"; 
        echo "<p class='bold-text'>Title:</p><p>" . $row['the_title'] . "</p>"; 
        echo "<p class='bold-text'>Description:</p><p>" . $row['the_description'] . "</p>"; 
        echo "<p class='bold-text'>Status:</p><p>" . $row['the_status'] . "</p>"; 
        echo "<p class='bold-text'>Supervising Professor:</p><p>" . $row['prof_name'] . " " . $row['prof_surname'] . "</p>"; 
        echo "<p class='bold-text'>Time since assignment:</p><p>" . htmlspecialchars($time_since_assignment) . "</p>";
       
        
        // λήψη των ονομάτων των συνεπιβ.
        $prof1_id = $row['co_prof_1_id'];
        $prof2_id = $row['co_prof_2_id'];
        $query_prof1 = "SELECT prof_name, prof_surname FROM professor WHERE prof_id = ?";
        $stmt_prof1 = $con->prepare($query_prof1);
        $stmt_prof1->bind_param("i", $prof1_id);
        $stmt_prof1->execute();
        $result_prof1 = $stmt_prof1->get_result();
        $prof1 = $result_prof1->fetch_assoc();

        $query_prof2 = "SELECT prof_name, prof_surname FROM professor WHERE prof_id = ?";
        $stmt_prof2 = $con->prepare($query_prof2);
        $stmt_prof2->bind_param("i", $prof2_id);
        $stmt_prof2->execute();
        $result_prof2 = $stmt_prof2->get_result();
        $prof2 = $result_prof2->fetch_assoc();

        echo "<p class='bold-text'>Committee Professor 1:</p><p> " . $prof1['prof_name'] . " " . $prof1['prof_surname'] . "</p>";
        echo "<p class='bold-text'>Committee Professor 2:</p><p> " . $prof2['prof_name'] . " " . $prof2['prof_surname'] . "</p>";
    } else {
        echo "<p>No details found for this thesis.</p>";
    }
    $stmt->close();
} else {
    //ερωτημα για την ευρεση των διπλβματιων
    $query1 = "SELECT the_id, the_title FROM thesis WHERE the_status = 'ongoing' OR the_status = 'under review'";
    $result = mysqli_query($con, $query1);

    // έλεγχος αν επιστραφηκαν αποτελεσματα
    if (mysqli_num_rows($result) > 0) {
        echo "<h1>Thesis Titles (Ongoing or Under Review)</h1>";
        echo "<table>";
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr><td><a href='#' class='thesis-link' data-id='" . $row['the_id'] . "'>" . $row['the_title'] . "</a></td></tr>";
        }
        echo "</table>";
    } else {
        echo "<p>No theses found with status 'ongoing' or 'under review'.</p>";
    }
    

//κλείσιμο σύνδεσης με βάση
mysqli_close($con);
}?>

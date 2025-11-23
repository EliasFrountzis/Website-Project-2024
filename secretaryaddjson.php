
<?php
//έλεγχος αν ανέβηκε το αρχείο
if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
    $fileTmpPath = $_FILES['file']['tmp_name'];
    $fileName = $_FILES['file']['name'];
    $fileSize = $_FILES['file']['size'];
    $fileType = $_FILES['file']['type'];
    $fileNameCmps = explode(".", $fileName); 
    $fileExtension = strtolower(end($fileNameCmps)); //εξασφαλίση συνέπειας στον έλεγχο τύπου αρχείου

    // έλεγχος αν έχει μορφη JSON
    if ($fileExtension === 'json') {
        //διαβασμα αρχείου
        $jsonData = file_get_contents($fileTmpPath);
        //παίρνει τα δεδομένα που είναι αποθηκ στο jsonData τα αποκωδικοποιεί σε php πίνακα με το true ότι τα δεδομένα δεν γίνονται αντικείμενο
        $data = json_decode($jsonData, true);

        //σύνδεση με βάση
        $DATABASE_HOST = 'localhost';
        $DATABASE_USER = 'root';
        $DATABASE_PASS = '';
        $DATABASE_NAME = 'thesis_distributed';
        $con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);

        //έλεγχος σύνδεσης με βάση
        if (mysqli_connect_errno()) {
            die('Failed to connect to MySQL: ' . mysqli_connect_error());
        }

        //εισαγωγή φοιτητών
        if (isset($data['students'])) {
            foreach ($data['students'] as $student) {
                $stmt = $con->prepare('INSERT INTO student (st_id, st_name, st_surname, st_number, st_ad_street, st_ad_number, st_ad_city, st_ad_postcode, st_father_name, st_landline, st_mobile, st_email) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
                $stmt->bind_param('isssssssssss', $student['id'], $student['name'], $student['surname'], $student['student_number'], $student['street'], $student['number'], $student['city'], $student['postcode'], $student['father_name'], $student['landline_telephone'], $student['mobile_telephone'], $student['email']);
                if (!$stmt->execute()) {
                    header('Location: secretary_addjson.php?message=4');
                    exit();
                }
            }
        }

        //εισαγωγή καθηγητών
        if (isset($data['professors'])) {
            foreach ($data['professors'] as $professor) {
                $stmt = $con->prepare('INSERT INTO professor (prof_id, prof_name, prof_surname, prof_email, prof_topic, prof_landline, prof_mobile, prof_department, prof_university) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)');
                $stmt->bind_param('issssssss', $professor['id'], $professor['name'], $professor['surname'], $professor['email'], $professor['topic'], $professor['landline'], $professor['mobile'], $professor['department'], $professor['university']);
                if (!$stmt->execute()) {
                    header('Location: secretary_addjson.php?message=4');
                    exit();
                }
            }
        }

        $stmt->close();
        mysqli_close($con);
        header('Location: secretary_addjson.php?message=1');
        
    } else {
        header('Location: secretary_addjson.php?message=2');
    }
} else {
    header('Location: secretary_addjson.php?message=3');
}
?>

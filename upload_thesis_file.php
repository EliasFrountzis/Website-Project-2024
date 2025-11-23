<?php
session_start();
$con = mysqli_connect("localhost", "root", "", "thesis_distributed");

if (!$con) {
    die(json_encode(["error" => "Connection failed: " . mysqli_connect_error()]));
}

$student_id = isset($_SESSION['log_id']) ? intval($_SESSION['log_id']) : 0;
$target_dir = "uploads/";
$target_file = $target_dir . basename($_FILES["thesisFile"]["name"]);
$uploadOk = 1;
$fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

// Check if file already exists
if (file_exists($target_file)) {
    $uploadOk = 0;
    die(json_encode(["error" => "Sorry, file already exists."]));
}

// Check file size
if ($_FILES["thesisFile"]["size"] > 5000000) {
    $uploadOk = 0;
    die(json_encode(["error" => "Sorry, your file is too large."]));
}

// Allow certain file formats
if ($fileType != "pdf" && $fileType != "doc" && $fileType != "docx") {
    $uploadOk = 0;
    die(json_encode(["error" => "Sorry, only PDF, DOC, and DOCX files are allowed."]));
}

// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    die(json_encode(["error" => "Sorry, your file was not uploaded."]));
// if everything is ok, try to upload file
} else {
    if (move_uploaded_file($_FILES["thesisFile"]["tmp_name"], $target_file)) {
        // Insert file information into database
        $query = "
            INSERT INTO student_thesis_supporting_material (st_sup_mat_st_id, st_thesis_supporting_material) 
            VALUES (?, ?)
        ";
        $stmt = $con->prepare($query);
        $stmt->bind_param('is', $student_id, $target_file);
        $stmt->execute();
        $stmt->close();

        echo json_encode(["success" => true]);
    } else {
        die(json_encode(["error" => "Sorry, there was an error uploading your file."]));
    }
}

mysqli_close($con);
?>

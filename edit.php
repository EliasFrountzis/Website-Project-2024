<?php


include("sindesi.php");

if (isset($_POST["edit"])) {
    $street = mysqli_real_escape_string($conn, $_POST["street"]);
    $number = mysqli_real_escape_string($conn, $_POST["number"]);
    $city = mysqli_real_escape_string($conn, $_POST["city"]);
    $postcode = mysqli_real_escape_string($conn, $_POST["postcode"]);
    $email = mysqli_real_escape_string($conn, $_POST["email"]);
    $landlinetelephone = mysqli_real_escape_string($conn, $_POST["landline_telephone"]);
    $mobiletelephone = mysqli_real_escape_string($conn, $_POST["mobile_telephone"]);
    $id = mysqli_real_escape_string($conn, $_POST["id"]);

    $sqlUpdate = "UPDATE student SET street = '$street', number = '$number', city = '$city', postcode = '$postcode', email = '$email', landline_telephone = '$landlinetelephone', mobile_telephone = '$mobiletelephone' WHERE id='$id'";
    if(mysqli_query($conn, $sqlUpdate)){
        session_start();
        $_SESSION["update"] = "Το προϊόν επεξεργάστηκε επιτυχώς!";
        header("Location:student_profile.php");
    }else{
        die("Αποτυχία επεξεργασίας");
    }
}   
?>

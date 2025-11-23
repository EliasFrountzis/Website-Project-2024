<?php
//για χειρισμό της προσθηκκης και της επεξεργασίας θέματος 1 προβλήμα στο edi

class thema
{


    public static $alerts = [];

    public static function connect()
    {
        $conn = new PDO("mysql:host=localhost;dbname=thesis_distributed", "root", "");
        return $conn;
    }
    public static function insert($the_topic, $the_title, $the_description, $the_description_file)
    {
        $add = thema::connect()->prepare("INSERT INTO thesis(the_id,the_topic,the_title,the_description,the_description_file)VALUES('',?,?,?,?)");
        $add->execute(array($the_topic, $the_title, $the_description, $the_description_file));
        if ($add) {
            thema::$alerts[] = 'Add';
        } else {
            thema::$alerts[] = 'No add';
        }
    }

    public static function update($the_id, $the_topic, $the_title, $the_description, $the_description_file)
    {
        $update = self::connect()->prepare("UPDATE thesis SET the_topic = ?, the_title = ?, the_description = ?, the_description_file = ? WHERE the_id = ?");
        $update->execute([$the_topic, $the_title, $the_description, $the_description_file, $the_id]);
        if ($update) {
            self::$alerts[] = 'Update';
        } else {
            self::$alerts[] = 'No update';
        }
    }

    public static function select()
    {
        $list = thema::connect()->prepare("SELECT * FROM thesis");
        $list->execute();
        $fetch = $list->fetchAll(PDO::FETCH_ASSOC);
        return $fetch;
    }
}



if (isset($_POST['addsubject'])) {

    $the_topic = $_POST['the_topic'];
    $the_title = $_POST['the_title'];
    $the_description = $_POST['the_description'];
    if (isset($_FILES['file'])) {
        if ($_FILES['file']['type'] == 'application/pdf') {
            $the_description_file = $_FILES['file']['name'];
            move_uploaded_file($_FILES['file']['tmp_name'], 'thefile/' . $the_description_file);
        }

        if (!empty($the_title) && !empty($the_description_file)) {
            thema::insert($the_topic, $the_title, $the_description, $the_description_file);
        } elseif (!empty($the_title) && empty($the_description_file)) {
            thema::insert($the_topic, $the_title, $the_description, null);
        } elseif (empty($the_topic) || empty($the_description)) {
            //thema::$alerts[] = 'Fill the fields';
            die("You should add the topic , the title , the description and if you want a pdf description file!");
        } else {
            thema::$alerts[] = 'Fill the fields';
        }
        header("Location:teacher_thesisList.php");
    }
}

include('sindesi.php');

//2)ΠΡΟΒΛΗΜΑ στην επεξεργασία στην προσθήκη νέου pdf den το περνάει τον τιτλο
if (isset($_POST["editsubject"])) {
    $the_id = $_POST['the_id'];
    $the_topic = mysqli_real_escape_string($conn, $_POST["the_topic"]);
    $the_title = mysqli_real_escape_string($conn, $_POST["the_title"]);
    $the_description = mysqli_real_escape_string($conn, $_POST["the_description"]);
    $the_description_file = null;
    if (isset($_FILES['file']) && $_FILES['file']['type'] == 'application/pdf') {
        $the_description_file = 'thefile/' . $_FILES['file']['name'];
        move_uploaded_file($_FILES['file']['tmp_name'], $the_description_file);
    }

    $sqlUpdate = "UPDATE thesis SET the_topic = '$the_topic', the_title = '$the_title', the_description = '$the_description', the_description_file = '$the_description_file' WHERE the_id = '$the_id'";
    if (mysqli_query($conn, $sqlUpdate)) {
        session_start();
        $_SESSION["update"] = "Update success!";
        header("Location:teacher_thesisList.php");
    } else {
        die("Update error");
    }
}

<?php
require './teacheraddsubject.php';
?>

<!DOCTYPE html>
<html lang="el">

<head>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <meta charset="utf-8">
    <title>Menu</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="welcome_nosingin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <meta name="viewport" content="width=device-width,initial-scale=1.0" />
</head>

<nav>
    <input type="checkbox" id="check">
    <label for="check" class="checkbtn">
        <i class="fa fa-bars"></i>
    </label>
    <img class="image" src="images/logo_oldmoney.png" alt="thesis old money logo">

    <ul>
        <li><a href="teacher_showthesissubjects.html">Thesis Subjects</a></li>
        <li><a href="teacherassingthesis.html">Assign Thesis to student</a></li>
        <li><a href="teacher_thesisList.html">Thesis List</a></li>
        <li><a href="teacher_Invites.html">Invites</a></li>
        <li><a href="teacher_statistics.html">Statistics</a></li>
        <li><a href="teacher_thesismanage.html">Thesis management</a></li>
    </ul>
    

    <form action="teacheraddsubject.php" method="post">
                    <?php
                    if(isset($_GET["the_id"])){
                        include("sindesi.php");
                        $the_id = $_GET["the_id"];
                        $sql = "SELECT * FROM thesis WHERE the_id = $the_id";
                        $result = mysqli_query($conn, $sql);
                        $row = mysqli_fetch_array($result);
                    ?>
                           <div class="seethroughbox">
        <h2 class="form-title">Update thesis</h2>

            <div class="main-user-info">
            <div class="user-input-box">
                    <label for="the_topic">Τopic</label>
                    <input type="text"
                        id="the_topic"
                        name="the_topic"
                        value="<?php echo $row["the_topic"];?>" />
                </div>
            
            
                <div class="user-input-box">
                    <label for="the_title">Τitle</label>
                    <input type="text"
                        id="the_title"
                        name="the_title"
                        value="<?php echo $row["the_title"];?>" />
                </div>

                <div class="user-input-descr">
                    <label for="the_description">Description</label>
                    <input class="textareathesis" name="the_description" rows="10" cols="25"
                       value="<?php echo $row["the_description"];?>" />
                </div>

                <div class="user-input-box">
                    <label for="file">Choose a pdf file:</label>
                    <input type="file" id="file" name="file" accept=".pdf"
                     />
                </div>

             

                            <input type="hidden" name="the_id" value=<?php echo $the_id; ?> name="the_id">
                            <div class="form-submit-btn"><input type="submit" name="editsubject" value="Submit">  </div>
                    
                            <?php 
                            }else{
                             echo "<h3>NO</h3>";}
                            
                            ?>
                
               
                
                    </div>

            </form>  
        </div>

</body>
            
</html>
                            
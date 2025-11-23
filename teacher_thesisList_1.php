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
    <meta name="viewport" content="width=device-width,initial-scale=1.0"/>
</head>
<body>
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
    </nav>

    
         <?php 
          if (count(thema::select())>0){
            $fetch=thema::select();
            foreach ($fetch as $value){
                ?>
                  
                <div class="show_subjects-box">

         <b> <?php echo $value['thesistitle'];?></b> </br>
          <?php echo $value['thesisdescription'];?>
          <a href="images/><?php echo $value['img'];?>" download="<?php echo $value['img'];?>"><?php echo $value['img'];?> </a>
          <a href="teachereditsubjectform.php?id=<?php echo $value["id"]; ?>">Επεξεργασία</a>
                </div>
    </div>
    <?php 
    }
} ?>
        </div>
</div>

        
            <a class="create_subjects-box" href="teacheraddsubjectform.php"> <h2>Add a subject</h2>
            <img class="imagescroll" src="images/addthesisscroll.png" alt="addthesisscroll">
        </a>
       
        
    </div>
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
    <div>
        <div class="seethroughbox1">
            <h2 class="form-title">Add new thesis</h2>
            <form action="professor_addthesis.php" method="post" enctype="multipart/form-data">
                <div class="main-user-info">
                    <div class="user-input-box">
                        <label for="the_topic">Topic</label>
                        <input type="text" class="form-control" id="the_topic" name="the_topic" placeholder="Topic" />
                    </div>
                    <div class="user-input-box">
                        <label for="thesistitle">Τitle</label>
                        <input type="text" class="form-control" id="thesistitle" name="thesistitle" placeholder="Title" />
                    </div>
                    <div class="user-input-box">
                        <label for="thesisdescription">Description</label>
                        <textarea class="textareathesis" class="form-control" name="thesisdescription" rows="10" cols="15" placeholder="Description"></textarea>
                    </div>
                    <div class="user-input-box">
                        <label for="file">Choose a pdf file(optional):</label>
                        <input type="file" class="form-control" id="file" name="file" accept=".pdf">
                    </div>
                    <div class="form-submit-btn">
                        <input type="submit" name="addsubject" value="Submit">
                    </div>
                </div>
            </form>
        </div>
</body>
</html>
